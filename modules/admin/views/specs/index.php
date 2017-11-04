<?php
use app\models\Specs\SpecsPack;

$this->registerJsFile('/js/admin/restricted.js',['position' => static::POS_END]);
/** @var SpecsPack $items */

?>

<div class="container-fluid">
    <form method="post" class="submitWarn" action="/admin/specs/save?categoryId=<?= $categoryId ?>">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <!--<input type="hidden" name="category_id" value="">-->
    <table class="table">
        <thead>
        <tr>
            <th><nobr><input type="checkbox" id="selectAll" value=""></nobr></th>
            <th>
                <label>Spec Id:</label>
            </th>
            <th>
                <label>Имя:</label>
            </th>
            <th>
                <label>Описание:</label>
            </th>
            <th>
                <button type="submit" data-action="/admin/specs/delete?categoryId=<?= $categoryId ?>" id="deleteSpec" name="delete" value="1" class="btn btn-primary">Удалить</button>
            </th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td>
                <span id="SpecId">New</span>
            </td>
            <input type="hidden" name="specs[new][category_id]" value="<?= $categoryId ?> ">

            <td class="form-group">
                <input type="text" class="form-control" id="SpecName" name="specs[new][name]" value="">
            </td>
            <td class="form-group">
                <textarea class="form-control" id="SpecDescription" name="specs[new][description]"></textarea>
            </td>
            <td></td>
            <td >
                <button type="submit" data-action="/admin/specs/save?categoryId=<?= $categoryId ?>" name="save" value="new" class="btn btn-primary">Add</button>
            </td>
        </tr>
        <?php
            for ($items->first(); $items->current(); $items->next()) {
        ?>
            <tr>
                <input type="hidden" name="specs[<?= $items->spec_id ?>][spec_id]" value="<?= $items->spec_id ?>" />
                <input type="hidden" name="specs[<?= $items->spec_id ?>][original_name]" value="<?= $items->original_name ?>" />
                <input type="hidden" name="specs[<?= $items->spec_id ?>][category_id]" value="<?= $items->category_id ?>" />

                <td>
                    <input type="checkbox" id="selectAll" name="specIds[]" value="<?= $items->spec_id ?>">
                </td>
                <td>
                    <span id="SpecId<?= $items->spec_id ?>"><?= $items->spec_id ?></span>
                </td>
                <td class="form-group">
                    <input type="text" class="form-control" id="SpecName[<?= $items->spec_id ?>]" name="specs[<?= $items->spec_id ?>][name]" value="<?= $items->name ?>">
                </td>
                <td class="form-group">
                    <textarea class="form-control" id="inputGroupDescription[<?= $items->spec_id ?>]" name="specs[<?= $items->spec_id ?>][description]"><?= $items->description ?></textarea>
                </td>
                <td>
                    <button type="button" class="restricted btn btn-primary" data-container="container<?= $items->spec_id ?>">Варианты</button>
                </td>
                <td >
                    <button type="submit" data-action="/admin/specs/save?categoryId=<?= $categoryId ?>" name="save" value="<?= $items->spec_id ?>" class="btn btn-primary">Save</button>
                </td>
            </tr>
            <tr class="container<?= $items->spec_id ?>">
                <td></td>
                <td><p><label>Id:</label></p></td>
                <td><label>Имя:</label></td>
            </tr>
            <tr class="container<?= $items->spec_id ?>">
                <input type="hidden" name="specs_restricted_values[new<?= $items->spec_id ?>][spec_id]" value="<?= $items->spec_id ?>" />
                <td></td>
                <td>
                    <span id="SpecRestIdNew<?= $items->spec_id ?>">New</span>
                </td>
                <td>
                    <input name="specs_restricted_values[new<?= $items->spec_id ?>][value]" type="text" class="form-control" id="SpecRestValueNew<?= $items->spec_id ?>" value="">
                </td>
            </tr>

                <?php
                if ($items->specs_restricted_values != null) {
                    $restricted = $items->specs_restricted_values;
                    for ($restricted->first(); $restricted->current(); $restricted->next()) {
                        ?>
                        <tr class="container<?= $items->spec_id ?>">
                            <input type="hidden" name="specs_restricted_values[<?= $restricted->specs_restricted_values_id ?>][specs_restricted_values_id]" value="<?= $restricted->specs_restricted_values_id  ?>" />
                            <input type="hidden" name="specs_restricted_values[<?= $restricted->specs_restricted_values_id ?>][spec_id]" value="<?= $items->spec_id ?>" />
                            <td>
                                <input type="checkbox" id="selectAll" name="specsRestrictedIds[<?= $items->spec_id ?>][]" value="<?= $restricted->specs_restricted_values_id ?>">
                            </td>
                            <td>
                                <span id="SpecRestId<?= $restricted->specs_restricted_values_id ?>">
                                    <?= $restricted->specs_restricted_values_id ?>
                                </span>
                            </td>
                            <td>
                                <input name="specs_restricted_values[<?= $restricted->specs_restricted_values_id ?>][value]" type="text" class="form-control" id="SpecRestValue<?= $restricted->specs_restricted_values_id ?>" value="<?= $restricted->value ?>">
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