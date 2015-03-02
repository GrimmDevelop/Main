@extends('layout')

@section('body')
    <div class="row">
        <div class="col-md-12" ng-controller="searchController" ng-init="loadFilter('{{ $filter_key }}')">
            <form class="search-form" role="form" ng-submit="search()">
                <tabset>
                    <tab heading="filter">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <p class="input-group">
                                    <input type="text" class="form-control" datepicker-popup="dd.MM.yyyy" ng-model="startDate.date" is-open="startDate.opened" min-date="startDate.minDate" max-date="startDate.maxDate" datepicker-options="dateOptions" close-text="Close" />
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
                        <div class="form-group row" ng-repeat="field in currentFilter.fields">
                            <div class="col-md-2 control-label"><select class="form-control" ng-model="field.code" ng-options="code for code in letterInfo.codes"></select></div>
                            <div class="col-md-2"><select class="form-control" ng-model="field.compare">
                                <option>equals</option>
                                <option>contains</option>
                                <option>starts with</option>
                                <option>ends with</option>
                            </select></div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" ng-model="field.value" typeahead="value for value in fieldTypeahead($viewValue, field)" />
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger" ng-click="removeField(field)" tooltip="remove field"><span class="glyphicon glyphicon-minus"></span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" ng-click="addField()" tooltip="add field"><span class="glyphicon glyphicon-plus"></span></button>
                        </div>
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-title="start search">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
@if(Sentry::check())
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" ng-repeat="filter in filters" ng-class="{ 'active': currentFilter.id == filter.id }" ng-click="loadFilter(filter)">@{{ $index + 1  }}</button>
                            <button type="button" class="btn btn-default" ng-click="newFilter()" data-toggle="tooltip" title="save as new filter" ng-disabled="currentFilter.fields.length == 0">+</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default"
                                ng-click="saveFilter()" ng-disabled="currentFilter.id == null"
                                data-toggle="tooltip" title="save current filter"><span class="glyphicon glyphicon-floppy-disk"></span></button>
                            <button type="button" class="btn btn-default"
                                ng-click="deleteFilter()" ng-disabled="currentFilter.id == null"
                                data-toggle="tooltip" title="delete current filter"><span class="glyphicon glyphicon-trash"></span></button>
                            <button type="button" class="btn btn-default"
                                ng-click="publicFilter()" ng-disabled="currentFilter.id == null || currentFilter.filter_key != null"
                                data-toggle="tooltip" title="public filter"><span class="glyphicon glyphicon-share-alt"></span></button>
                        </div>

                        <span class="btn-group" ng-show="currentFilter.id != null && currentFilter.filter_key != null">
                            <a href="{{ url('search') }}/@{{ currentFilter.filter_key }}" target="_blank" class="btn btn-default">{{ url('search') }}/@{{ currentFilter.filter_key }}</a>
                            <a href="@{{ sendMail() }}" class="btn btn-default"><span class="glyphicon glyphicon-envelope"></span></a>
                        </span>
@endif
                    </tab>
                    <tab heading="display">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Display Codes:</p>
                            </div>
                        </div>
                        <div fields-selection="displayCodes" fields-codes="letterInfo.codes"></div>
                    </tab>
                </tabset>
            </form>

            <div class="shortcut-help">
                <p>Press <kbd>?</kbd> for a list of all available shortcuts!</p>
            </div>

            <div class="result" ng-show="results.total > 0">

                <p>total: @{{ results.total }}</p>

                <div class="row">
                    <div class="col-md-2" style="margin: 20px 0;">
                        <select class="form-control" ng-model="itemsPerPage" ng-change="search()" ng-options="option for option in itemsPerPageOptions"></select>
                    </div>
                    <div class="col-md-10">
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
                    <div class="col-md-2" style="margin: 20px 0;">
                        <select class="form-control" ng-model="itemsPerPage" ng-change="search()" ng-options="option for option in itemsPerPageOptions"></select>
                    </div>
                    <div class="col-md-10">
                        <pagination total-items="results.total" ng-model="currentPage" ng-change="search()" items-per-page="results.per_page"
                            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
                    </div>
                </div>
            </div>
            <div class="result" ng-show="results.total == 0">
                nothing found
            </div>
        </div>
    </div>
@stop
