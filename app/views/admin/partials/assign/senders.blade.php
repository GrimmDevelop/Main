<div class="modal-header">
    <h3 class="modal-title">Assigning for letter #@{{ letter.id }}</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

    <h3>Search</h3>
    <div class="row" ng-repeat="info in letter.information | filterCode:['senders']">
        <div class="col-sm-10">
            <input type="text" class="form-control" ng-model="info.data" typeahead="person for person in typeSearch($viewValue)" typeahead-min-length="3">
        </div>
        <div class="col-sm-2">
            <a href ng-click="search(info.data)" class="btn btn-success form-control"><span class="glyphicon glyphicon-search"></span></a>
        </div>
    </div>

    <h3>Assinged senders</h3>
    <div class="row" ng-repeat="sender in letter.senders">
        <div class="col-sm-10"><a href person-preview="sender.id">@{{ sender.name_2013 }}</a></div>
        <div class="col-sm-2"><a href ng-click="unassign(sender)" class="btn btn-danger form-control">-</a></div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()" ng-disabled="selectedItem == null">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>