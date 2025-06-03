<?php
use yii\helpers\Html;
use yii\web\JqueryAsset;

$this->title = 'Főoldal';

$this->registerCssFile("https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css");
$this->registerJsFile("https://code.jquery.com/ui/1.13.2/jquery-ui.min.js", ['depends' => [JqueryAsset::class]]);

$this->registerJs(<<<JS
    $('.draggable-group').draggable({
        containment: '#drag-area'
    });
JS);
?>

<div class="site-index">
    <h1>Heroes Sportegyesület csapatai</h1>

    <?php if (!empty($groups)): ?>
        <div id="drag-area" style="border: 1px solid #ccc; padding: 20px; min-height: 400px;">
            <?php
            function renderGroups($groups, $depth = 0)
            {
                foreach ($groups as $group) {
                    echo Html::tag('div', strtoupper($group['name']), [
                        'class' => 'draggable-group',
                        'style' => 'margin-left:' . ($depth * 30) . 'px; font-size:30px; text-transform:uppercase; cursor:move; padding:5px; border:1px solid #aaa; margin-bottom:5px; background-color:#f0f0f0; width: 30%;',
                    ]);
                    if (!empty($group['children'])) {
                        renderGroups($group['children'], $depth + 1);
                    }
                }
            }

            renderGroups($groups);
            ?>
        </div>
    <?php else: ?>
        <p>Nincs jogosultság a csoportok megtekintéséhez.</p>
    <?php endif; ?>
</div>
