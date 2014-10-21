
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="$parent.itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
        </div>

        <pagination total-items="persons.total" ng-model="$parent.currentPage" ng-change="reload()" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>

        <div class="checkbox">
            <label><input type="checkbox" ng-model="$parent.autoGenerated" ng-change="reload()"> auto generated only</label>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th></th>
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
                <tr ng-repeat="person in persons.data" ng-class="person.auto_generated == 1 ? 'warning' : ''">
                    <td><a href ng-click="show(person.id)">@{{ person.id }}</a></td>
                    <td>@{{ person.name_2013 }}</td>
                    <td>@{{ person.sended_letters_count }}</td>
                    <td>@{{ person.received_letters_count }}</td>
                </tr>
            </tbody>
        </table>

        <pagination total-items="persons.total" ng-model="$parent.currentPage" ng-change="reload()" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>