<div class="form-group">
    <label><?=$field->label?> <?=$field->is_required == '1' ? '*': ''?></label>
    <select class="form-control" name="<?=$field->field_name?>" id="<?=$field->field_name?>"  <?=$field->is_required == '1' ? 'required': ''?>>
        <?php
        $fieldValues = preg_split('/\r\n|\r|\n/', $field->data);
        foreach($fieldValues as $k => $v):
            $item = explode('|', $v);
        ?>
            <option value="<?=$item[0]?>"><?=isset($item[1]) ? $item[1] : $item[0]?></option>
        <?php endforeach; ?>
    </select>    
</div>