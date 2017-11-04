<?php
use app\models\Options\OptionsPack;

$this->registerJsFile('/js/admin/restricted.js',['position' => static::POS_END]);
/**
 * @var OptionsPack $items
 * @var int $categoryId
 */

?>

<div class="container-fluid">
    <form method="post" class="submitWarn" action="/admin/options/save">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <!--<input type="hidden" name="category_id" value="">-->
    <table class="table">
        <thead>
        <tr>
            <th><nobr><input type="checkbox" id="selectAll" value=""></nobr></th>
            <th>
                <label>Option Id:</label>
            </th>
            <th>
                <label>Имя:</label>
            </th>
            <th>
                <label>Описание:</label>
            </th>
            <th>
                <label>Стоимость:</label>
            </th>
            <th><button type="submit" data-action="/admin/options/delete?categoryId=<?= $categoryId ?>" id="deleteOption" name="delete" value="1" class="btn btn-primary">Удалить</button></th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td>
                <span id="OptionId">New</span>
            </td>
            <input type="hidden" name="options[new][category_id]" value="<?= $categoryId ?> ">
            <td class="form-group">
                <input type="text" class="form-control" id="OptionName" name="options[new][name]" value="">
            </td>
            <td class="form-group">
                <textarea class="form-control" id="OptionDescription" name="options[new][description]"></textarea>
            </td>
            <td class="form-group">
                <input type="number" step="any"  class="form-control" id="OptionPrice" name="options[new][price]" value="">
            </td>
            <td></td>
            <td >
                <button type="submit" data-action="/admin/options/save?categoryId=<?= $categoryId ?>" name="save" value="new" class="btn btn-primary">Add</button>
            </td>
        </tr>
        <?php
            for ($items->first(); $items->current(); $items->next()) {
        ?>
            <tr>
                <input type="hidden" name="options[<?= $items->option_id ?>][option_id]" value="<?= $items->option_id ?>" />
                <input type="hidden" name="options[<?= $items->option_id ?>][original_name]" value="<?= $items->original_name ?>" />
                <input type="hidden" name="options[<?= $items->option_id ?>][category_id]" value="<?= $items->category_id ?>" />

                <td>
                    <input type="checkbox" name="optionIds[]" value="<?= $items->option_id ?>">
                </td>
                <td>
                    <span id="OptionId<?= $items->option_id ?>"><?= $items->option_id ?></span>
                </td>
                <td class="form-group">
                    <input type="text" class="form-control" id="OptionName[<?= $items->option_id ?>]" name="options[<?= $items->option_id ?>][name]" value="<?= $items->name ?>">
                </td>
                <td class="form-group">
                    <textarea class="form-control" id="inputGroupDescription[<?= $items->option_id ?>]" name="options[<?= $items->option_id ?>][description]"><?= $items->description ?></textarea>
                </td>
                <td class="form-group">
                    <input type="number" step="any"  class="form-control" id="OptionPrice[<?= $items->option_id ?>]" name="options[<?= $items->option_id ?>][price]" value="<?= $items->price ?>">
                </td>
                <td>
                    <button type="button" class="restricted btn btn-primary" data-container="container<?= $items->option_id ?>">Варианты</button>
                </td>
                <td >
                    <button type="submit" data-action="/admin/options/save?categoryId=<?= $categoryId ?>" name="save" value="<?= $items->option_id ?>" class="btn btn-primary">Save</button>
                </td>
            </tr>
            <tr class="container<?= $items->option_id ?>">
                <td></td>
                <td><p><label>Id:</label></p></td>
                <td><label>Имя:</label></td>
                <td><label>Цена:</label></td>
            </tr>
            <tr class="container<?= $items->option_id ?>">
                <input type="hidden" name="options_restricted_values[new<?= $items->option_id ?>][option_id]" value="<?= $items->option_id ?>" />
                <td></td>
                <td>
                    <span id="OptionRestIdNew<?= $items->option_id ?>">New</span>
                </td>
                <td>
                    <input name="options_restricted_values[new<?= $items->option_id ?>][value]" type="text" class="form-control" id="OptionRestValueNew<?= $items->option_id ?>" value="">
                </td>
                <td>
                    <input name="options_restricted_values[new<?= $items->option_id ?>][price]" type="text" class="form-control" id="OptionRestPriceNew<?= $items->option_id ?>" value="">
                </td>
            </tr>

                <?php
                if ($items->options_restricted_values != null) {
                    $restricted = $items->options_restricted_values;
                    for ($restricted->first(); $restricted->current(); $restricted->next()) {
                        ?>
                        <tr class="container<?= $items->option_id ?>">
                            <input type="hidden" name="options_restricted_values[<?= $restricted->options_restricted_values_id ?>][options_restricted_values_id]" value="<?= $restricted->options_restricted_values_id  ?>" />
                            <input type="hidden" name="options_restricted_values[<?= $restricted->options_restricted_values_id ?>][option_id]" value="<?= $items->option_id ?>" />
                            <input type="hidden" name="options_restricted_values[<?= $restricted->options_restricted_values_id ?>][original_value]" value="<?= $restricted->original_value  ?>" />
                            <td>
                                <input type="checkbox" name="optionsRestrictedIds[<?= $items->option_id ?>][]" value="<?= $restricted->options_restricted_values_id ?>">
                            </td>
                            <td>
                                <span id="OptionRestId<?= $restricted->options_restricted_values_id ?>">
                                    <?= $restricted->options_restricted_values_id ?>
                                </span>
                            </td>
                            <td>
                                <input name="options_restricted_values[<?= $restricted->options_restricted_values_id ?>][value]" type="text" class="form-control" id="OptionRestValue<?= $restricted->options_restricted_values_id ?>" value="<?= $restricted->value ?>">
                            </td>
                            <td>
                                <input name="options_restricted_values[<?= $restricted->options_restricted_values_id ?>][price]" type="text" class="form-control" id="OptionRestPrice<?= $restricted->options_restricted_values_id ?>" value="<?= $restricted->price ?>">
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
        <?php
        }
        ?>
        </tbody>
    </table>
    </form>
</div>