<div class="modal-header">
    <h3 class="modal-title">assign @{{ mode }} for letter - @{{ letter.id }}</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    <ul>
    <li ng-repeat="information in letter.informations | filterCode:['absendeort', 'absort_ers']">
        @{{ information.data }}
        <span>
            <a href ng-click="search(information.data)"><span class="glyphicon glyphicon-search"></span></a>
        </span>
        <div ng-repeat="location in resultList"><a href ng-click="select(location)">@{{ location.name }} @{{ location.latitude }} @{{ location.longitude }}</a></div>
    </li>
    </ul>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>