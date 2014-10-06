
<div class="row" ng-if="mode == 'index'">
    <div class="col-md-12">
        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="itemsPerPage" ng-change="reload(itemsPerPage, currentPage)" ng-options="option for option in itemsPerPageOptions"></select>
        </div>

        <pagination total-items="persons.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>

        <table class="table">
            <tr ng-repeat="person in persons.data" ng-click="show(person)">
                <td width="10%">@{{ person.id }}</td>
                <td>@{{ person.name_2013 }}</td>
            </tr>
        </table>

        <pagination total-items="persons.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="persons.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>

<div class="row" ng-if="mode == 'show'">
    <div class="col-md-12">
        <a ng-click="index()" class="btn btn-default">Back</a>
        <table>
            <tr>
                <td>@{{ currentPerson.id }}</td>
                <td>@{{ currentPerson.name_2013 }}</td>
            </tr>
        </table>
    </div>
</div>
