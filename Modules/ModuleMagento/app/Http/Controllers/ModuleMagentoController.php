<?php
declare(strict_types=1);

namespace Modules\ModuleMagento\Http\Controllers;

use Modules\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ModuleMagentoController extends BaseControllerApi
{
    public function generate(Request $request)
    {
        $vendor = ucfirst($request->input('vendor'));
        $module = ucfirst($request->input('module'));
        $moduleName = "{$vendor}_{$module}";
        
        $basePath = storage_path("app/modules/{$vendor}/{$module}");
        $etcPath = "{$basePath}/etc";

        // Tạo thư mục
        File::makeDirectory($etcPath, 0755, true, true);

        // registration.php
        File::put("{$basePath}/registration.php", "<?php
            use Magento\Framework\Component\ComponentRegistrar;

            ComponentRegistrar::register(ComponentRegistrar::MODULE, '{$moduleName}', __DIR__);
        ");

        // etc/module.xml
        File::put("{$etcPath}/module.xml", "<?xml version=\"1.0\"?>
            <config xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"urn:magento:framework:Module/etc/module.xsd\">
                <module name=\"{$moduleName}\" setup_version=\"1.0.0\"/>
            </config>
        ");

        // Tạo file zip
        $zipPath = storage_path("app/modules/{$moduleName}.zip");
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($basePath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        // Xóa thư mục sau khi zip (tùy chọn)
        File::deleteDirectory(storage_path("app/modules/{$vendor}"));

        // Trả về file zip để tải
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
