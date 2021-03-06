<div class="user-filters row">
    <div class="col-md-12">
        <div class="input-group filter-search">
            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
            <input type="text" class="form-control" placeholder="Search for saved filter" ng-model="directiveStatus.filter_search" ng-disabled="savedFilters.length == 0">
        </div>
    </div>
    <div class="col-md-12 filter-list">
        <div class="filter-list-container">
            <h4 ng-if="savedFilters.length == 0">No Filters saved!</h4>
            <a href="#" ng-click="selectFilter(filter)" class="filter-list-item" ng-class="{'active': selectedFilter.id==filter.id }" ng-repeat="filter in savedFilters | filter:directiveStatus.filter_search">@{{ filter.name }}</a>
        </div>
    </div>
    <div class="col-md-12">
        <div class="input-group new-filter">
            <input class="form-control" ng-model="directiveStatus.nameInput"
                   type="text" placeholder="Enter a name to save new filter"
                   ng-keyup="onNewFilterInput($event)" ng-disabled="filters.fields.length == 0" />
            <span class="input-group-btn">
                <button type="button" class="btn btn-default" ng-click="newFilter()"
                        data-toggle="tooltip" title="save as new filter"
                        ng-disabled="filters.fields.length == 0"><span class="glyphicon glyphicon-plus"></span>
                </button>
            </span>
        </div>
    </div>

    <div ng-if="selectedFilter.name">
        <h4 class="col-md-12">Filter: @{{ selectedFilter.name }} <span ng-if="selectedFilter.shared">(Shared)</span></h4>
        <div class="col-md-12" style="margin-bottom: 10px;">
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
                <input type="text" class="form-control" placeholder="Publish filter to see link" ng-model="directiveStatus.publicUrl" readonly tooltip-append-to-body="true" tooltip-placement="right" tooltip="Public URL to share the filter" />
            </div>
        </div>
        <div class="col-md-3" ng-if="!selectedFilter.shared">
            <p>Aktionen:</p>
        </div>
        <div class="col-md-9" ng-if="!selectedFilter.shared">
            <div class="btn-group" dropdown>
                <button type="button" class="btn" ng-class="{'btn-warning': directiveStatus.changed, 'btn-default': !directiveStatus.changed}"
                        ng-click="saveFilter()" ng-disabled="selectedFilter.id == null"
                        tooltip="Save Current Filter" tooltip-append-to-body="true" title="Save Current Filter"><span
                            class="glyphicon glyphicon-floppy-disk"></span></button>
                <button type="button" class="btn btn-default"
                        ng-click="deleteFilter()" ng-disabled="selectedFilter.id == null"
                        tooltip="Delete Filter" tooltip-append-to-body="true" title="Delete this Filter"><span class="glyphicon glyphicon-trash"></span>
                </button>
                <ul class="dropdown-menu">
                    <li ng-if="selectedFilter.filter_key == null"><a href="#" ng-click="publishFilter()"><span class="glyphicon glyphicon-link"></span> Publish Filter</a></li>
                    <li><a href="#" ng-click="sendMailForFilter()"><span
                                    class="glyphicon glyphicon-envelope"></span> Share Filter via E-Mail</a></li>
                </ul>
                <button type="button" class="btn btn-default"
                        tooltip="Share Filter" tooltip-append-to-body="true" title="Share Filter" dropdown-toggle>
                    <span class="glyphicon glyphicon-share-alt"></span>

                </button>

            </div>
        </div>
    </div>
</div>