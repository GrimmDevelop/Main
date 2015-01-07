
<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-8">
        <pagination total-items="persons.total" ng-model="currentPage" ng-change="reload()" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
    <div class="col-md-2" style="margin: 20px 0;">
        <div class="btn-group input-group">
            <input type="text" class="form-control" placeholder="name filter" ng-model="startsWith">
            <span class="input-group-btn"><button class="btn btn-default" ng-click="reload()">apply</button></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="checkbox">
            <label><input type="checkbox" ng-model="autoGenerated" ng-change="reload()"> auto generated only</label>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="10%">id</th>
                    <th ng-click="orderBy('name_2013')">
                        name
                        <span class="dropup" ng-show="currentOrderBy=='name_2013' && currentOrderByDirection == 'asc'">
                            <span class="caret"></span>
                        </span>
                        <span class="caret" ng-show="currentOrderBy=='name_2013' && currentOrderByDirection == 'desc'"></span>
                    </th>
                    <th width="15%" ng-click="orderBy('sended_letters_count')">
                        sended letters
                        <span class="dropup" ng-show="currentOrderBy=='sended_letters_count' && currentOrderByDirection == 'asc'">
                            <span class="caret"></span>
                        </span>
                        <span class="caret" ng-show="currentOrderBy=='sended_letters_count' && currentOrderByDirection == 'desc'"></span>
                    </th>
                    <th width="15%" ng-click="orderBy('received_letters_count')">
                        received letters
                        <span class="dropup" ng-show="currentOrderBy=='received_letters_count' && currentOrderByDirection == 'asc'">
                            <span class="caret"></span>
                        </span>
                        <span class="caret" ng-show="currentOrderBy=='received_letters_count' && currentOrderByDirection == 'desc'"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="person in persons.data" ng-class="{'warning': person.auto_generated}">
                    <td><a href person-edit="person.id">@{{ person.id }}</a></td>
                    <td>@{{ person.name_2013 }}</td>
                    <td>@{{ person.sended_letters_count }}</td>
                    <td>@{{ person.received_letters_count }}</td>
                </tr>
            </tbody>
        </table>

        <pagination total-items="persons.total" ng-model="currentPage" ng-change="reload()" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>