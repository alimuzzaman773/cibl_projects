<div class="form-group">
    <label><?=$field->label?> <?=$field->is_required == '1' ? '*': ''?></label>
    <input type="text" value="" <?=$field->is_required == '1' ? 'required': ''?> name="<?=$field->field_name?>" id="<?=$field->field_name?>" class="form-control" />
</div>
