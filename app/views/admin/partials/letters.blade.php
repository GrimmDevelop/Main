<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
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
            <tab heading="Filter" active="tabstatus.filter">
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
                        <label><input type="checkbox" ng-model="showLettersWithErrors.from"/> show only letters with
                            from errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.to"/> show only letters with to
                            errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.senders"/> show only letters with
                            sender errors</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" ng-model="showLettersWithErrors.receivers"/> show only letters
                            with receiver errors</label>
                    </div>
                    <button type="submit" class="btn btn-primary" tooltip="apply filter">
                        <span class="glyphicon glyphicon-refresh"></span>
                        Apply filters
                    </button>
                </form>
            </tab>
            <tab heading="Quick Search" active="tabstatus.quicksearch">
                <form class="form-horizontal" ng-submit="findByIdentifierOrCode()" name="quicksearchForm">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="letter_id">Letter ID:</label>

                        <div class="control-group col-md-10">
                            <input type="text" class="form-control" name="quicksearchId" ng-model="quicksearch.id"
                                   focus-on="quicksearch.Id"/>
                            <span class="help-block">This will search for letters that currently have the given ID or had this in 1992 or 1997.</span>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error': !quicksearchForm.quicksearchCode.$valid}">
                        <label class="col-md-2 control-label" for="letter_code">Letter Code:</label>

                        <div class="control-group col-md-10">
                            <input type="text" class="form-control" name="quicksearchCode" ng-model="quicksearch.code"
                                   ng-pattern="/[0-9]{8}\.[0-9]{2}/"/>
                            <span class="help-block" ng-show="!quicksearchForm.quicksearchCode.$valid">Invalid format of the given letter code!</span>
                            <span class="help-block">Access a letter directly by its code which has the form <code>yyyymmdd.nn</code> where y are the digits of the year, m the month, d the day and n the order count.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <button type="submit" class="btn btn-primary" on-focus="filter.apply"
                                    ng-disabled="!quicksearchForm.quicksearchCode.$valid"><span
                                        class="glyphicon glyphicon-search"></span> Search
                            </button>
                        </div>
                    </div>
                </form>
            </tab>
            <tab heading="Display properties" active="tabstatus.display">
                <div class="form-group">
                    <label class="control-label">View:</label>
                    <select ng-model="display.currentView" ng-change="changeView(display.currentView)"
                            ng-options="item for item in display.views"></select>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" ng-model="display.shortEdit"/> Show edit button in columns and fields <span
                                class="glyphicon glyphicon-pencil"></span></label>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p>Display Codes:</p>
                    </div>
                </div>

                <div fields-selection="fields" fields-codes="letterInfo.codes"></div>
            </tab>
            <tab active="tabstatus.trashed">
                <tab-heading class="text-danger">
                    <span class="glyphicon glyphicon-trash"></span> Trash
                </tab-heading>

                <button type="button" class="btn"
                        ng-class="{'btn-danger': trashedChanged, 'btn-default': !trashChanged}"
                        ng-click="loadTrashedLetters()">Reload trash
                </button>
            </tab>
        </tabset>

        <div class="shortcut-help">
            <p>Press <kbd>?</kbd> for a list of all available shortcuts!</p>
        </div>
    </div>
</div>

<div ng-if="!tabstatus.trashed">
    <div class="row">
        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="pagination.itemsPerPage" ng-change="reload()"
                    ng-options="option for option in pagination.itemsPerPageOptions"></select>
        </div>
        <div class="col-md-10">
            <pagination total-items="letters.total" ng-model="pagination.currentPage" ng-change="reload()"
                        items-per-page="letters.per_page"
                        max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;"
                        last-text="&raquo;"
                        boundary-links="true"></pagination>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" ng-include="display.currentView"></div>
    </div>

    <div class="row">
        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="pagination.itemsPerPage" ng-change="reload()"
                    ng-options="option for option in pagination.itemsPerPageOptions"></select>
        </div>
        <div class="col-md-10">
            <pagination total-items="letters.total" ng-model="pagination.currentPage" ng-change="reload()"
                        items-per-page="letters.per_page"
                        max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;"
                        last-text="&raquo;"
                        boundary-links="true"></pagination>
        </div>
    </div>
</div>

<div class="row" ng-show="tabstatus.trashed">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="letter in trashedLetters.data" ng-show="letter.deleted_at">
                <td>@{{ letter.id }}</td>
                <td>@{{ letter.code }}</td>
                <td><a href ng-click="restoreLetter(letter)" class="text-danger">restore</a></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
