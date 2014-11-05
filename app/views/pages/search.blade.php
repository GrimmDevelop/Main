@extends('layout')

@section('body')
    <div class="row">
        <div class="col-md-12" ng-controller="searchController" ng-init="loadFilter('{{ $filter_key }}')">
            <form role="form" ng-submit="search()">
                <h1>Filter</h1>

                <div class="form-group row" ng-repeat="field in currentFilter.fields">
                    <div class="col-md-2 control-label"><select class="form-control" ng-model="field.code" ng-options="code for code in codes"></select></div>
                    <div class="col-md-2"><select class="form-control" ng-model="field.compare">
                        <option>equals</option>
                        <option>contains</option>
                        <option>starts with</option>
                        <option>ends with</option>
                    </select></div>
                    <div class="col-md-7"><input type="text" class="form-control" ng-model="field.value" /></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger" ng-click="removeField(field)">-</button></div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" ng-click="addField()">+</button>
                </div>
                <button type="submit" class="btn btn-primary">start search</button>
@if(Sentry::check())
                <div class="btn-group">
                    <button type="button" class="btn btn-default" ng-repeat="filter in filters" ng-class="{ 'active': currentFilter.id == filter.id }" ng-click="loadFilter(filter)">@{{ $index + 1  }}</button>
                    <button type="button" class="btn btn-default" ng-click="newFilter()" ng-disabled="currentFilter.fields.length == 0">+</button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-default" ng-click="saveFilter()" ng-disabled="currentFilter.id == null">save current</button>
                    <button type="button" class="btn btn-default" ng-click="deleteFilter()" ng-disabled="currentFilter.id == null">delete current</button>
                    <button type="button" class="btn btn-default" ng-click="publicFilter()" ng-disabled="currentFilter.id == null || currentFilter.filter_key != null">public current</button>
                </div>

                <span class="btn-group" ng-show="currentFilter.id != null && currentFilter.filter_key != null">
                    <a href="{{ url('search') }}/@{{ currentFilter.filter_key }}" target="_blank" class="btn btn-default">{{ url('search') }}/@{{ currentFilter.filter_key }}</a>
                    <a href="@{{ sendMail() }}" class="btn btn-default"><span class="glyphicon glyphicon-envelope"></span></a>
                </span>
@endif
            </form>

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
                        <p><a class="btn btn-default" href ng-click="viewDistanceMap()">compute distance map</a></p>
                    </div>
                </div>

                <div class="row" ng-repeat="letter in results.data">
                    <div class="col-md-1"><a href letter-preview="letter.id">@{{ letter.id }}</a></div>
                    <div class="col-md-9">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">code</th>
                                    <th>data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="info in letter.information | filterCode:['absender', 'empfaenger', 'absendeort', 'absort_ers', 'empf_ort']">
                                    <td>@{{ info.code }}</td>
                                    <td>@{{ info.data }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2">
                        <a href letter-from-to-preview="letter.id" class="btn btn-default form-control">
                            <span class="glyphicon glyphicon-map-marker"></span>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <span class="glyphicon glyphicon-envelope"></span>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <span class="glyphicon glyphicon-map-marker"></span>
                        </a>
                </div>
            </div>
            <div class="result" ng-show="results.total == 0">
                nothing found
            </div>
        </div>
    </div>
@stop
