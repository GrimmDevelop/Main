
<div class="row">
   <div class="col-md-12">
       <div class="messages">
           <alert ng-if="message.error" type="danger" close="closeMessage()">@{{ message.error.message }}</alert>
           <alert ng-if="message.success" type="success" close="closeMessage()">@{{ message.success.message }}</alert>
       </div>
   </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h1>Import</h1>

        <h3 ng-click="mode = 'letter'">Letter</h3>
        <div ng-show="mode == 'letter'">
            <p ng-show="selectedLetterFile != null">@{{ selectedLetterFile }} &raquo; <a href="#" ng-click="startLetterImport($event)">start</a></p>
            <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLetterFile"></file-browser>
        </div>

        <h3 ng-click="mode = 'location'">Locations</h3>
        <div ng-show="mode == 'location'">
            <p>@{{ selectedLocationFile }}</p>
            <file-browser types="['text/plain']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLocationFile"></file-browser>
        </div>

        <h3 ng-click="mode = 'person'">Persons</h3>
        <div ng-show="mode == 'person'">
            <p ng-show="selectedPersonFile != null">@{{ selectedPersonFile }} &raquo; <a href="#" ng-click="startPersonImport($event)">start</a></p>
            <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedPersonFile"></file-browser>
        </div>
    </div>
</div>
