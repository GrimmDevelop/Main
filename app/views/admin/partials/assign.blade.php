
<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

        <h1>Assign</h1>

        <p>per command: <input ng-model="numbersToAssign"></p>

        <a href ng-click="from()">locations from</a><br>
        <a href ng-click="to()">locations to</a><br>
        <a href ng-click="receivers()">receivers</a><br>
        <a href ng-click="senders()">senders</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12" ng-show="locationsToCheck.length > 0">
        <h1>Locations</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="location in locationsToCheck | orderBy:'name'">
                    <td>@{{ location.name }}</td>
                    <td><a href ng-click="search(location)"><span class="glyphicon glyphicon-search"></span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12" ng-show="personsToCheck.length > 0">
        <h1>Persons</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                    <th>generate</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="person in personsToCheck | orderBy:'name'">
                    <td>@{{ person.name }}</td>
                    <td><a href ng-click="searchPerson(person)"><span class="glyphicon glyphicon-search"></span></a></td>
                    <td><a href ng-click="autoGenerate(person)"><span class="glyphicon glyphicon-new-window"></span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
