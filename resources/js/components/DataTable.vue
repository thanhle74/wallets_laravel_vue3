<template>
    <table id="myTable">
        <thead>
            <tr class="bg-royal-purple text-amethyst-purple">
                <slot name="thead"></slot>
            </tr>
        </thead>
        <tbody>
            <slot name="tbody"></slot>
        </tbody>
    </table>
</template>
