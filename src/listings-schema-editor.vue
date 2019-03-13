<template>
  <div id="pno-schema-app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "listings-schema-editor"
};
</script>

<style lang="scss">
	.popper.schema-delete {
		-webkit-box-shadow: 0 3px 5px rgba(0,0,0,0.2);
		box-shadow: 0 3px 5px rgba(0,0,0,0.2);
		background-color: #32373c;
		padding: 10px 15px;
		color: rgba(240, 245, 250, 0.7);
		p {
			color: rgba(240, 245, 250, 0.7);
			margin: 0;
		}
		border:none;

		.button {
			position: relative;
			top:-1px;
			margin-left: 5px;
			margin-right: 0 !important;
			&:active,
			&:focus,
			&:hover {
				vertical-align: initial
			}
		}

		&[x-placement^="top"] .popper__arrow {
			border-color: #32373c transparent transparent transparent
		}

		&[x-placement^="left"] .popper__arrow {
			border-color: transparent transparent transparent #32373c;
		}

		&[x-placement^="right"] .popper__arrow {
			border-color: transparent #32373c transparent transparent;
		}

		&[x-placement^="bottom"] .popper__arrow {
			border-color: transparent transparent #32373c transparent;
		}
	}
</style>
