<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$command = $this->param(0);
$app     = app();

if ($command) {
    $help = "$command/help.php";
    
    if ($this->canExecute($help)) {
        $this->execute($help, $this->paramList());
        exit(-1);
    }
    
    printf("Не найдена справочная информация для `$app %s'. Используте `$app help' для справки.\n", $command);
    exit(-1);
}

?>
Использование

<?=app()?> [ПАРАМЕТРЫ] [КОМАНДА] [ПАРАМЕТРЫ КОМАНДЫ]

КОМАНДЫ
<?php 
foreach (glob(ExpandPath('@commands/*'), GLOB_ONLYDIR) as $folder) {
    $command     = basename($folder);
    $description = Meta($folder)->get('description');
    printf("%10s\t%s\n", $command, $description);
}

?>

Используйте `<?=$app?> help КОМАНДА' для получения справки по конкретной команде.
<?php
exit(-1);
