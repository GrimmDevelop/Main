
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

        <accordion close-others="true">
            <accordion-group heading="Letters">
                <p ng-show="selectedLetterFile != null">@{{ selectedLetterFile }} &raquo; <a href="#" ng-click="startLetterImport($event, selectedLetterFile)">start</a></p>
                <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLetterFile"></file-browser>
            </accordion-group>
            <accordion-group heading="Locations">
                <p ng-show="selectedLocationFile != null">@{{ selectedLocationFile }} &raquo; <a href="#" ng-click="startLocationImport($event, selectedLocationFile)">start</a></p>
                <file-browser types="['text/plain']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedLocationFile"></file-browser>
            </accordion-group>
            <accordion-group heading="Persons">
                <p ng-show="selectedPersonFile != null">@{{ selectedPersonFile }} &raquo; <a href="#" ng-click="startPersonImport($event, selectedPersonFile)">start</a></p>
                <file-browser types="['application/x-dbf']" select-files="true" select-directories="false" enable-drag-drop="false" ng-model="selectedPersonFile"></file-browser>
            </accordion-group>
        </accordion>
    </div>
</div>
