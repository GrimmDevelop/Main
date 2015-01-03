
<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <input class="form-control" ng-model="openLetterId" placeholder="type in a letter id">
    </div>
    <div class="col-sm-10">
        <button class="btn btn-default" ng-click="openLetterWithId()"><span class="glyphicon glyphicon-edit"></span></button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <tabset>
            <tab heading="filter">
                <form role="form">
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.from" ng-change="reload()" /> show only letters with from errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.to" ng-change="reload()" /> show only letters with to errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.senders" ng-change="reload()" /> show only letters with sender errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.receivers" ng-change="reload()" /> show only letters with receiver errors</label>
                    </div>
                </form>
            </tab>
            <tab heading="display">
                <div fields-selection="fields"></div>
            </tab>
            <tab heading="view">
                <select ng-model="view.current" ng-options="item for item in view.all"></select>
            </tab>
        </tabset>
    </div>
</div>

<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-10">
        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload()" items-per-page="letters.per_page"
                    max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>

<div class="row">
    <div class="col-md-12" ng-include="view.current"></div>
</div>

<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload()" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-10">
        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload()" items-per-page="letters.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>
