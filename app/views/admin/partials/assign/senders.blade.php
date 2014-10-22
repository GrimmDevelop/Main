<div class="modal-header">
    <h3 class="modal-title">assign @{{ mode }} for letter - @{{ letter.id }}</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

    <ul>
        <li ng-repeat="info in letter.information | filterCode:['senders']">
            <input type="text" class="form-control" ng-model="info.data" typeahead="person for person in typeSearch($viewValue)" typeahead-min-length="3">
            <span ng-if="info.code == 'senders'">
                <a href ng-click="search(info.data)"><span class="glyphicon glyphicon-search"></span></a>
            </span>
        </li>
    </ul>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>