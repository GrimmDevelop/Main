
<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <tabset>
            <tab heading="Letters">
                <div ng-controller="exportLettersController" ng-include="'admin/partials/export.letters'"></div>
            </tab>
            <tab heading="Locations">
                <div></div>
            </tab>
            <tab heading="Persons">
                <div></div>
            </tab>
        </tabset>
    </div>
</div>
