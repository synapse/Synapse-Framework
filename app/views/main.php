<decorate template="html5">
	<messages/>

	<h1>1. Hello decorator</h1>
	<h1>2. Hello decorator</h1>

	<if condition="$hello==21">
		<h2>If block here</h2>
	<elseif condition="1==3"/>
		<h2>Elseif 1==3</h2>
	<elseif condition="1==4"/>
		<h2>Elseif 1==4</h2>
	<else/>
		<h2>Else block here</h2>
	</if>

	<include template="include"/>
</decorate>