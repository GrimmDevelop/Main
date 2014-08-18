
<div class="row">
    <div class="col-md-12">
        Import

        <h3>Letter</h3>
        <p>@{{ selectedLetterFile }}</p>
        <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLetterFile"></file-browser>

        <h3>Locations</h3>
        <p>@{{ selectedLocationFile }}</p>
        <file-browser types="['text/plain']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLocationFile"></file-browser>
    </div>
</div>
