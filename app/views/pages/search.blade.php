@extends('layout')

@section('body')
    <div class="row">
        <div class="col-md-12" ng-controller="searchController">
            <div class="search-form">
                <tabset>
                    <tab heading="Filter" active="tabstatus.filter">
                        <div class="row">
                            <form class="search-form form-horizontal" role="form" ng-submit="search()">
                            @if (Sentry::check() || !empty($filter_key))
                                <div class="col-md-9">
                            @else
                                <div class="col-md-12">
                            @endif
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <p class="input-group">
                                                <input type="text" class="form-control" datepicker-popup="dd.MM.yyyy" ng-model="startDate.date" is-open="startDate.opened" min-date="startDate.minDate" max-date="startDate.maxDate" datepicker-options="dateOptions" close-text="Close" focus-on="filter.start" />
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" ng-click="open(startDate, $event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="input-group">
                                                <input type="text" class="form-control" datepicker-popup="dd.MM.yyyy" ng-model="endDate.date" is-open="endDate.opened" min-date="endDate.minDate" max-date="endDate.maxDate" datepicker-options="dateOptions" close-text="Close" />
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" ng-click="open(endDate, $event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <field-group not-removable="true" group="currentFilter.filter" codes="letterInfo.codes" on-remove="removeTopGroup(group)" on-change="fieldChanged()"></field-group>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-title="start search">
                                                <span class="glyphicon glyphicon-search"></span> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                @if(Sentry::check())
                                    <div class="col-md-3">
                                    <user-filters shared="{{ $filter_key }}" filters="currentFilter.filter"></user-filters>
                                    </div>
                                @elseif(!empty($filter_key))
                                    <div class="col-md-3">
                                        <shared-filter key="{{ $filter_key }}" filters="currentFilter.filter"></shared-filter>
                                    </div>
                                @endif
                            </div>

                    </tab>
                    <tab heading="Quick Search" active="tabstatus.quicksearch">
                        <form class="form-horizontal" ng-submit="findByIdentifierOrCode()" name="quicksearchForm">
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="letter_id">Letter ID:</label>
                                <div class="control-group col-md-10">
                                    <input type="text" class="form-control" name="quicksearchId" ng-model="quicksearch.id" focus-on="quicksearch.Id" />
                                    <span class="help-block">This will search for letters that currently have the given ID or had this in 1992 or 1997.</span>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{'has-error': !quicksearchForm.quicksearchCode.$valid}">
                                <label class="col-md-2 control-label" for="letter_code">Letter Code:</label>
                                <div class="control-group col-md-10">
                                    <input type="text" class="form-control" name="quicksearchCode" ng-model="quicksearch.code" ng-pattern="/^[0-9]{8}\.[0-9]{2}$/" />
                                    <span class="help-block" ng-show="!quicksearchForm.quicksearchCode.$valid">Invalid format of the given letter code!</span>
                                    <span class="help-block">Access a letter directly by its code which has the form <code>yyyymmdd.nn</code> where y are the digits of the year, m the month, d the day and n the order count.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary" ng-disabled="!quicksearchForm.quicksearchCode.$valid"><span class="glyphicon glyphicon-search"></span> Search</button>
                                </div>
                            </div>
                        </form>
                    </tab>
                    <tab heading="Display Properties">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Display Codes:</p>
                            </div>
                        </div>
                        <div fields-selection="displayCodes" fields-codes="letterInfo.codes"></div>
                    </tab>
                </tabset>

            </div>

            <div class="shortcut-help">
                <p>Press <kbd>?</kbd> for a list of all available shortcuts!</p>
            </div>

            <div class="result" ng-show="results.total > 0">

                <div class="row">
                    <div class="col-md-2" style="margin-top: 25px; margin-bottom: 20px;"><p><strong>@{{ results.total }}</strong> Results</p></div>
                    <div class="col-md-2" style="margin-top: 20px; margin-bottom: 20px;">
                        <select class="form-control" ng-model="itemsPerPage" ng-change="search()" ng-options="option for option in itemsPerPageOptions"></select>
                    </div>
                    <div class="col-md-8">
                        <pagination total-items="results.total" ng-model="currentPage" ng-change="search()" items-per-page="results.per_page"
                            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p><a class="btn btn-default" href ng-click="viewDistanceMap()" ng-disabled="!{{ json_encode(Sentry::check()) }}">compute distance map</a></p>
                    </div>
                </div>

                <div ng-repeat="letter in results.data">
                    <hr class="letter-separator">
                    <div class="row">
                        <div class="col-md-1">
                            <span tooltip="open letter" style="display: inline-block;">
                                <a href letter-preview="letter.id" class="btn btn-default">#@{{ letter.id }}</a>
                            </span>
                        </div>
                        <div class="col-md-11">
                            <span tooltip="display sender and receiver overview" style="display: inline-block;">
                                <a href class="btn btn-default"
                                        letter-from-to-preview="letter.id" ng-show="letter.from_id && letter.to_id">
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="20%">code</th>
                                        <th>data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="info in letter.information | filterCode:displayCodes">
                                        <td>@{{ info.code }}</td>
                                        <td>@{{ info.data }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-2" style="margin-top: 25px; margin-bottom: 20px;"><p><strong>@{{ results.total }}</strong> Results</p></div>
                    <div class="col-md-2" style="margin-top: 20px; margin-bottom: 20px;">
                        <select class="form-control" ng-model="itemsPerPage" ng-change="search()" ng-options="option for option in itemsPerPageOptions"></select>
                    </div>
                    <div class="col-md-8">
                        <pagination total-items="results.total" ng-model="currentPage" ng-change="search()" items-per-page="results.per_page"
                                    max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
                    </div>
                </div>
            </div>
            <div class="result nothing-found" ng-show="results.total == 0">
                <div class="row">
                    <div class="col-md-12">
                        <h3>No Matches Found!</h3>
                        <p>There were no results for the given parameters!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
