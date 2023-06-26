<?php

    $path_node_modules = is_string($path_node_modules ??= '') ? $path_node_modules : '';
    $path_resources_js = is_string($path_resources_js ??= '') ? $path_resources_js : '';
    $path_module = is_string($path_module ??= '') ? $path_module : '';
?>

<script>
    const pathNodeModules = '<?= $path_node_modules ?>';
    const pathResourcesJs = '<?= $path_resources_js ?>';
    const pathModule = '<?= $path_module ?>';
</script>
