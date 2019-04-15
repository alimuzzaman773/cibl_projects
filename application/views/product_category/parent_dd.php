<select class="form-control" id="field-parent_id" name="parent_id" parent_id ="<?=$catInfo?>">
    <option value=""></option>
    <?php foreach ($categoryList as $c): ?>
    <option value="<?=$c->pc_id?>" <?=($c->pc_id == $catInfo ? 'selected="selected"' : '')?>><?= ucfirst($c->name)?></option>
    <?php endforeach; ?>
</select>
