<select class="form-control" id="field-type" name="type">
    <option value=""></option>
    <?php foreach ($type as $val=>$t): ?>
    <option value="<?=$val?>" <?=($value == $val ? 'selected="selected"' : '')?>><?= ucfirst($t)?></option>
    <?php endforeach; ?>
</select>
