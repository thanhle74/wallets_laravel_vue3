// Vue-only solution to generate Magento 2 module without Laravel backend
// Using JSZip to create ZIP files and download locally

import JSZip from 'jszip';

const generateMagentoModule = async (vendor, module, options) => {
    const moduleName = `${vendor}_${module}`;
    const zip = new JSZip();
    const basePath = `${vendor}/${module}`;

    // registration.php
    zip.file(`${basePath}/registration.php`, `<?php
use Magento\\Framework\\Component\\ComponentRegistrar;
ComponentRegistrar::register(ComponentRegistrar::MODULE, '${moduleName}', __DIR__);
`);

    // etc/module.xml
    zip.file(`${basePath}/etc/module.xml`, `<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="${moduleName}" setup_version="1.0.0"/>
</config>
`);

    // Create table if selected
    if (options.createTable) {
        zip.file(`${basePath}/etc/db_schema.xml`, `<?xml version="1.0"?>
<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">
    <table name="${module}_custom_table" resource="default" engine="innodb" comment="Custom Table">
        <column name="entity_id" xsi:type="int" unsigned="true" nullable="false" identity="true"/>
        <constraint xsi:type="primary" referenceId="PRIMARY">
            <column name="entity_id"/>
        </constraint>
    </table>
</schema>
`);
    }

    // Create event & observer if selected
    if (options.createEvent) {
        const eventName = options.eventName || 'custom_event';
        zip.file(`${basePath}/etc/events.xml`, `<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Event/etc/events.xsd">
    <event name="${eventName}">
        <observer name="${module}_observer" instance="${vendor}\\${module}\\Observer\\CustomObserver"/>
    </event>
</config>
`);
        zip.file(`${basePath}/Observer/CustomObserver.php`, `<?php
namespace ${vendor}\\${module}\\Observer;
use Magento\\Framework\\Event\\ObserverInterface;
class CustomObserver implements ObserverInterface {
    public function execute(\Magento\\Framework\\Event\\Observer $observer) {
        // Custom Logic
    }
}
`);
    }

    // Generate ZIP and trigger download
    const zipBlob = await zip.generateAsync({ type: 'blob' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(zipBlob);
    link.download = `${moduleName}.zip`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

export default generateMagentoModule;
