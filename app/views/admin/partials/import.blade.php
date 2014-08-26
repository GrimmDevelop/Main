
<div class="row">
    <div class="col-md-12">
        Import

        <h3 ng-click="importLetters = !importLetters">Letter</h3>
        <div ng-if="importLetters">
            <p>@{{ selectedLetterFile }}</p>
            <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLetterFile"></file-browser>
        </div>

        <h3 ng-click="importLocations = !importLocations">Locations</h3>
        <div ng-if="importLocations">
            <p>@{{ selectedLocationFile }}</p>
            <file-browser types="['text/plain']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLocationFile"></file-browser>
        </div>
    </div>
</div>
