<?php

    $path_node_modules = is_string($path_node_modules ??= '') ? $path_node_modules : '';
    $path_resources_js = is_string($path_resources_js ??= '') ? $path_resources_js : '';
?>

<script>
    const pathNodeModules = '<?= $path_node_modules ?>';
    const pathResourcesJs = '<?= $path_resources_js ?>';
</script>
