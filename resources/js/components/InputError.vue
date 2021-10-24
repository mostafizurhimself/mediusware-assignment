<template>
	<div v-show="message">
		<p class="text-danger">
			{{ message }}
		</p>
	</div>
</template>

<script>
export default {
	props: ["message"],
};
</script>
