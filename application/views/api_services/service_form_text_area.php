<div class="form-group">
    <label><?=$field->label?> <?=$field->is_required == '1' ? '*': ''?></label>
    <textarea name="<?=$field->field_name?>" <?=$field->is_required == '1' ? 'required': ''?> id="<?=$field->field_name?>" class="form-control" row="4"></textarea>
</div>
