
<div class="row" ng-show="mode == 'index'">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="itemsPerPage" ng-change="reload(itemsPerPage, currentPage)" ng-options="option for option in itemsPerPageOptions"></select>
        </div>

        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="letters.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>

        <table class="table">
            <tr ng-repeat="letter in letters.data">
                <td><a href ng-click="show(letter)">@{{ letter.id }}</a></td>
                <td>@{{ letter.code }}</td>
                <td>
                    <div ng-repeat="information in letter.informations | filterCode:['absendeort','absort_ers','empf_ort','dr']">@{{ information.code }} @{{ information.data }}</div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <a href ng-click="openPersonModal(letter, 'senders')" class="btn btn-@{{ letter.senders.length == (letter.informations | countCode:'senders') ? 'default' : 'primary' }}" title="assign senders">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </a>
                            </div>
                            <div ng-repeat="sender in letter.senders">@{{ sender.name_2013 }}</div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <a href ng-click="openPersonModal(letter, 'receivers')" class="btn btn-@{{ letter.receivers.length == (letter.informations | countCode:'receivers') ? 'default' : 'primary' }}" title="assign receivers">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-user"></span>
                                </a>
                            </div>
                            <div ng-repeat="receiver in letter.receivers">@{{ receiver.name_2013 }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div ng-show="letter.from != null">@{{ letter.from.name }}</div>
                            <div ng-show="letter.from == null">
                                <a href ng-click="openLocationModal(letter, 'from')" class="btn btn-primary" title="assign from location">
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div ng-show="letter.to != null">@{{ letter.to.name }}</div>
                            <div ng-show="letter.to == null">
                                <a href ng-click="" class="btn btn-primary" title="assign to location">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <pagination total-items="letters.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="letters.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>

<div class="row" ng-show="mode == 'show'">
    <div class="col-md-12">
        <a href="#" ng-click="index($event)">Back</a>
        <table class="table">
            <tr>
                <td>@{{ currentLetter.id }}</td>
                <td>@{{ currentLetter.code }}</td>
                <td><div ng-repeat="information in currentLetter.informations">@{{ information.code }} @{{ information.data }}</div></td>
            </tr>
        </table>
    </div>
</div>