
<div class="row" ng-if="mode == 'index'">
    <div class="col-md-12">
        <table class="table">
            <tr ng-repeat="letter in letters" ng-click="show(letter)">
                <td>@{{ letter.id }}</td>
                <td>@{{ letter.code }}</td>
            </tr>
        </table>
    </div>
</div>
