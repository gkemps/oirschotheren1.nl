<?php
	require_once("functions/layout.inc.php");
	head();
	?>
		<div id="tabs">
    <ul>
        <li style="margin-left: 1px" id="tabHeader1" class="currenttab"><a href="javascript:void(0)" onClick="toggleTab(1,3)"><span>Grafisch</span></a></li>
        <li id="tabHeader2"><a href="javascript:void(0)" onClick="toggleTab(2,3)" ><span>Score Sheet</span></a></li>
        <li id="tabHeader3"><a href="javascript:void(0)" onclick="toggleTab(3,3)"><span>Verslag</span></a></li>
    </ul>
    </div>
    <div id="tabscontent">
        <div id="tabContent1" class="tabContent" style="display:none;">
            <br /><div>
			<?php
				include("wedstrijd_grafisch.php");
			?>
			</div>
        </div>

        <div id="tabContent2" class="tabContent" style="display:none;">
            <br /><div>Second Tab Content goes here</div>
        </div>

        <div id="tabContent3" class="tabContent" style="display:none;">
            <br /><div>Third Tab Content goes here</div>
        </div>
    </div><!--End of tabscontent-->
</div><!--End of tabs-->
<script type="text/javascript" language="javascript">
	toggleTab(1,3);
</script>
	<?php
	close();
?>