@extends('layout')

@section('body')
    <div class="row">
        <div class="col-md-12" ng-controller="searchController">
            <form role="form">
                <h1>Filters</h1>
                <div class="form-group row" ng-repeat="filter in filters">
                    <div class="col-md-2 control-label"><select class="form-control" ng-model="filter.code" ng-options="code for code in codes"></select></div>
                    <div class="col-md-2"><select class="form-control" ng-model="filter.compare">
                        <option>equals</option>
                        <option>contains</option>
                        <option>starts with</option>
                        <option>ends with</option>
                    </select></div>
                    <div class="col-md-7"><input type="text" class="form-control" ng-model="filter.value" /></div>
                    <div class="col-md-1"><button type="button" class="btn btn-primary" ng-click="removeFilter(filter)">-</button></div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" ng-click="addFilter()">+</button>
                </div>
                <button type="button"  class="btn btn-primary" ng-click="search()">start search</button>
@if(Sentry::check())
                <button type="button" class="btn btn-default" ng-click="saveFilters()">save filters</button>
@endif
            </form>

            <div class="result" ng-show="results.total > 0">
                <pagination total-items="results.total" ng-model="currentPage" ng-change="search()" items-per-page="100"
                    max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>

                <div ng-repeat="letter in results.data" class="row">
                    <div class="col-md-2">@{{ letter.id }} @{{ letter.code }}</div>
                    <div class="col-md-8">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">code</th>
                                    <th>data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="information in letter.informations">
                                    <td>@{{ information.code }}</td>
                                    <td>@{{ information.data }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-1">
                        <a href ng-click="locationPreview(letter.from_id)" class="btn btn-default" ng-show="letter.from_id">
                             <span class="glyphicon glyphicon-map-marker"></span>
                             <span class="glyphicon glyphicon-arrow-right"></span>
                             <span class="glyphicon glyphicon-envelope"></span>
                        </a>
                        <a href ng-click="personPreview(person)" ng-repeat="person in letter.senders" class="btn btn-default">
                            <span class="glyphicon glyphicon-user"></span>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <span class="glyphicon glyphicon-envelope"></span>
                        </a>
                    </div>

                    <div class="col-md-1">
                        <a href ng-click="locationPreview(letter.to_id)" class="btn btn-default" ng-show="letter.to_id">
                            <span class="glyphicon glyphicon-envelope"></span>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <span class="glyphicon glyphicon-map-marker"></span>
                        </a>
                        <a href ng-click="personPreview(person)" ng-repeat="person in letter.receivers" class="btn btn-default">
                            <span class="glyphicon glyphicon-envelope"></span>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <span class="glyphicon glyphicon-user"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="result" ng-show="results.total == 0">
                nothing found
            </div>
        </div>
    </div>
@stop
