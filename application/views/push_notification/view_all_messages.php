<h1 class="title-underlined">Sent Messages</h1>
<section id="vamModule" data-ng-controller="vamController">
    <table class="table table-striped table-bordered" id="referenceTable">
        <thead>
            <tr class="bg-primary">
                <th style="text-align: center" >ID</th>
                <th style="text-align: center" >Headline</th>
                <th style="text-align: center" >Body</th>
                <th style="text-align: center" >Recepient</th>
                <th style="text-align: center" >Active/Inactive</th>
            </tr>
        </thead>
        <tbody>
            <tr data-ng-repeat="v in vam track by $index">
                <td style="text-align:center">{{v.messageId}}</td>
                <td style="text-align:center">{{v.headLine}}</td>
                <td style="text-align:center" data-ng-bind-html="v.body"></td>
                <td style="text-align:center">{{v.recipient}}</td>
                <td style="text-align:center" data-ng-class="{'bg-success' : v.active == 1}">{{v.active}}</td>
            </tr>
        </tbody>
    </table>


    <button id="writeMessage" data-ng-click="" class="btn btn-success">Write Message</button>
  
</section>

<?php
ci_add_js(asset_url() . 'app/vam_module.js');
?>
<script type="text/javascript" charset="utf-8">

    var app = app || {};

    var initialData = <?= $sentMessages ?>; //data for building initial table  
//    var vm = function () {
//        var self = this;
//        self.records = ko.observableArray(initialData);
//
//        $.each(self.records(), function (i, record) {  //build the checkboxes checked/unchecked
//
//            record.messageId = record.messageId;
//            record.headLine = record.headLine;
//            record.body = record.body;
//            record.recipient = record.recipient;
//
//
//            if (record.isActive === "1") {
//                record.active = "Active";
//                record.activeColor = ko.observable("green");
//            } else if (record.isActive === "0") {
//                record.active = "Inactive";
//                record.activeColor = ko.observable("red");
//            }
//        })
//
//
//        self.writeMessage = function (item) {
//            window.location = "<?php echo base_url(); ?>push_notification/writeMessage";
//        }
//
//    }
    //ko.applyBindings(new vm());

</script>

