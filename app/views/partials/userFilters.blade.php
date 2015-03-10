<div class="user-filters row">
    <div class="col-md-12">
        <div class="input-group">
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
    <div class="col-md-12">
        <div class="list-group">
            <a href="#" ng-click="selectFilter(filter)" class="list-group-item" ng-class="{'active': selectedFilter.id==filter.id }" ng-repeat="filter in savedFilters">@{{ filter.name }}</a>
        </div>
    </div>

    <div ng-if="selectedFilter.id">
        <h4 class="col-md-12">Filter: @{{ selectedFilter.name }}</h4>
        <div class="col-md-3">
            <p>Aktionen:</p>
        </div>
        <div class="col-md-9">
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
                    <li><a href="#" ng-click="sendMailForFilter()"><span
                                    class="glyphicon glyphicon-envelope"></span> Share Filter via E-Mail</a></li>
                    <li><a href="{{ url('search') }}/@{{ selectedFilter.filter_key }}" target="_blank"><span class="glyphicon glyphicon-link"></span> Permalink</a></li>
                </ul>
                <button type="button" class="btn btn-default"
                        tooltip="Share Filter" tooltip-append-to-body="true" title="Share Filter" dropdown-toggle>
                    <span class="glyphicon glyphicon-share-alt"></span>

                </button>

            </div>
        </div>
    </div>
</div>