
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
    <div class="col-sm-2">
        <button class="btn btn-default" letter-create><span class="glyphicon glyphicon-new-window"></span></button>
    </div>
    <div class="col-sm-10">

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <tabset>
            <tab heading="Filter">
                <form role="form" ng-submit="reload()">
                    <div class="form-group row" ng-repeat="field in currentFilter.fields">
                        <field-row field="field" codes="letterInfo.codes" on-remove="removeField(field)"></field-row>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" ng-click="addField()" tooltip="add field">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add Filter
                        </button>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.from" /> show only letters with from errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.to" /> show only letters with to errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.senders" /> show only letters with sender errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.receivers" /> show only letters with receiver errors</label>
                    </div>
                    <button type="submit" class="btn btn-primary" tooltip="apply filter">
                        <span class="glyphicon glyphicon-refresh"></span>
                        Apply filters
                    </button>
                </form>
            </tab>
            <tab heading="Display properties">
                <div class="form-group">
                    <label class="control-label">View:</label>
                    <select ng-model="display.currentView" ng-change="changeView(display.currentView)" ng-options="item for item in display.views"></select>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" ng-model="display.shortEdit" /> show <span class="glyphicon glyphicon-pencil"></span></label>
                </div>

                <div fields-selection="fields" fields-codes="letterInfo.codes"></div>
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
    <div class="col-md-12" ng-include="display.currentView"></div>
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
