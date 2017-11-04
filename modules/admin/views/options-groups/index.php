<?php
use app\models\OptionsGroups\OptionsGroupsPack;

/** @var OptionsGroupsPack $items */

?>
<div class="container-fluid">
    <form method="post" class="submitWarn" action="/admin/options-groups/save?categoryId=<?= $categoryId ?>">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <!--<input type="hidden" name="category_id" value="">-->
    <table class="table">
        <thead>
        <tr>
            <th><nobr><input type="checkbox" id="selectAll" value=""></nobr></th>
            <th>
                <label>Group Id:</label>
            </th>
            <th>
                <label>Имя:</label>
            </th>
            <th>
                <label>Описание:</label>
            </th>
            <th><button type="submit" data-action="/admin/options-groups/delete?categoryId=<?= $categoryId ?>" id="deleteOptionGroup" name="delete" value="1" class="btn btn-primary">Удалить</button></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td>
                <span id="inputGroupId">New</span>
            </td>
            <td class="form-group">
                <input type="text" class="form-control" id="inputGroupName" name="new[name]" value="">
            </td>
            <td class="form-group">
                <textarea class="form-control" id="inputGroupDescription" name="new[description]"></textarea>
            </td>
            <td></td>
            <td >
                <button type="submit" name="save" value="new" class="btn btn-primary">Add</button>
            </td>
        </tr>
        <?php
            for($items->first();$items->current();$items->next()) {
        ?>
            <tr>
                <input type="hidden" name="<?= $items->group_id ?>[group_id]" value="<?= $items->group_id ?>" />

                <td><nobr><input type="checkbox" name="groupIds[]" value="<?= $items->group_id ?>"></nobr></td>
                <td>
                    <span id="inputGroupId<?= $items->group_id ?>"><?= $items->group_id ?></span>
                </td>
                <td class="form-group">
                    <input type="text" class="form-control" id="inputGroupName[<?= $items->group_id ?>]" name="<?= $items->group_id ?>[name]" value="<?= $items->name ?>">
                </td>
                <td class="form-group">
                    <textarea class="form-control" id="inputGroupDescription[<?= $items->group_id ?>]" name="<?= $items->group_id ?>[description]"><?= $items->description ?></textarea>
                </td>
                <td>
                    <a href="/admin/options?groupId=<?= $items->group_id ?>">Опции</a>
                </td>
                <td >
                    <button type="submit" name="save" value="<?= $items->group_id ?>" class="btn btn-primary">Save</button>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    </form>
</div>