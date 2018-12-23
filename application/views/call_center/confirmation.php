<div class="confirm_container">
    <div class="btn-group special">
        <a class="btn btn-danger" href="<?php echo base_url(); ?>call_center/#/user_list">Cancel</a>
        <a class="btn btn-success" data-ng-click="approvedConfirmation()">Confirm</a>
    </div>
</div>

<div id="info_message"></div>

<div class="progress_bar">
    <div class="progress">
        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100"
             aria-valuemin="0" aria-valuemax="100" style="width:100%">
            User approve process in progress
        </div>

    </div>
</div>

<style>
    .confirm_container{
        margin: 50px auto !important;
        border: 1px solid #ddd;
        width: 300px;
        padding: 50px;
        border-radius: 5px;
        background-color: #efefef
    }

    .btn-group.special {
        display: flex;
    }

    .special .btn {
        flex: 1
    }

    .progress_bar{
        width:550px;
        margin: 30px auto;
    }
</style>