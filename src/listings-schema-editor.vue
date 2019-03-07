<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "listings-schema-editor"
};
</script>

<style lang="scss">
.l_admin-row:before,
.l_admin-row:after {
  content: " ";
  display: table;
}
.l_admin-row:after {
  clear: both;
}
.l_admin-row {
  *zoom: 1;
}

.l_admin-clearfix:before,
.l_admin-clearfix:after {
  content: " ";
  display: table;
}
.l_admin-clearfix:after {
  clear: both;
}
.l_admin-clearfix {
  *zoom: 1;
}

.l_admin-column {
  margin-bottom: 20px;
  position: relative;
  max-width: 100%;
  box-sizing: border-box;
}
.l_admin-no-gutter {
  margin-right: 0;
}

.no-gutter {
  margin-right: 0;
}
.middled,
.row .column.middled {
  float: none;
  margin: auto;
}
.l_admin-last {
  margin-right: 0;
}

.l_admin-area-wrapper {
  margin-left: -10px;
}

@media only screen and (max-width: 480px) {
  .l_admin-column.l_admin-span-1 {
    width: calc(100% / 6 - 20px);
  }
  .l_admin-column.l_admin-span-2 {
    width: calc(100% / 2 - 20px);
  }
}

@media only screen and (min-width: 481px) {

  .l_admin-area-wrapper {
    margin-left: -20px;
  }

  .l_admin-row {
    margin-left: -10px;
    margin-right: -10px;
  }
  .l_admin-column {
    float: left;
    margin-right: 10px;
    margin-left: 10px;
  }

  .l_admin-column.l_admin-span-6 {
    width: calc(100% / 2 - 20px);
  }
}

@media only screen and (min-width: 481px) and (max-width: 768px) {
  .l_admin-column.l_admin-span-2 {
    width: calc(100% / 3 - 20px);
  }
  .l_admin-column.l_admin-span-3 {
    width: calc(100% / 2 - 20px);
  }
  .l_admin-column.l_admin-span-7,
  .l_admin-column.l_admin-span-8,
  .l_admin-column.l_admin-span-9,
  .l_admin-column.l_admin-span-10,
  .l_admin-column.l_admin-span-11,
  .l_admin-column.l_admin-span-12 {
    float: none;
    clear: both;
  }
}

@media only screen and (min-width: 769px) {

  .l_admin-span-1 {
    width: calc(100% / 12 - 20px);
  }
  .l_admin-span-2 {
    width: calc(100% / 6 - 20px);
  }
  .l_admin-span-3 {
    width: calc(100% / 4 - 20px);
  }
  .l_admin-span-4 {
    width: calc(100% / 3 - 20px);
  }
  .l_admin-span-5 {
    width: calc((100% / 12) * 5 - 20px);
  }
  .l_admin-span-7 {
    width: calc((100% / 12) * 7 - 20px);
  }
  .l_admin-span-8 {
    width: calc((100% / 12) * 8 - 20px);
  }
  .l_admin-span-9 {
    width: calc((100% / 12) * 9 - 20px);
  }
  .l_admin-span-10 {
    width: calc((100% / 12) * 10 - 20px);
  }
  .l_admin-span-11 {
    width: calc((100% / 12) * 11 - 20px);
  }
  .l_admin-span-12 {
    width: calc(100% - 20px);
    clear: both;
    float: none;
  }
}

.l_admin-pull-right {
  float: right;
}
.l_admin-pull-left {
  float: left;
}
.l_admin-clear {
  clear: both;
}
.l_admin-no-push-bottom {
  margin-bottom: 0 !important;
}

.l_admin-push-top {
  margin-top: 20px !important;
}
.l_admin-push-top-small {
  margin-top: 10px !important;
}
.l_admin-push-top-medium {
  margin-top: 30px !important;
}
.l_admin-push-top-large {
  margin-top: 40px !important;
}
.l_admin-push-top-massive {
  margin-top: 60px !important;
}

.l_admin-push-bottom {
  margin-bottom: 20px !important;
}
.l_admin-push-bottom-small {
  margin-bottom: 10px !important;
}
.l_admin-push-bottom-medium {
  margin-bottom: 30px !important;
}
.l_admin-push-bottom-large {
  margin-bottom: 40px !important;
}
.l_admin-push-bottom-massive {
  margin-bottom: 60px !important;
}
.l_admin-push-right {
  margin-right: 20px !important;
}
.l_admin-push-right-small {
  margin-right: 10px !important;
}
.l_admin-push-right-medium {
  margin-right: 30px !important;
}
.l_admin-push-right-large {
  margin-right: 40px !important;
}
.l_admin-push-right-massive {
  margin-right: 60px !important;
}

.l_admin-push-left {
  margin-left: 20px !important;
}
.l_admin-push-left-small {
  margin-left: 10px !important;
}
.l_admin-push-left-medium {
  margin-left: 30px !important;
}
.l_admin-push-left-large {
  margin-left: 40px !important;
}
.l_admin-push-left-massive {
  margin-left: 60px !important;
}

.l_admin-push-right {
  margin-right: 20px !important;
}
.l_admin-push-right-small {
  margin-right: 10px !important;
}
.l_admin-push-right-medium {
  margin-right: 30px !important;
}
.l_admin-push-right-large {
  margin-right: 40px !important;
}
.l_admin-push-right-massive {
  margin-right: 60px !important;
}

.l_admin-push-left {
  margin-left: 20px !important;
}
.l_admin-push-left-small {
  margin-left: 10px !important;
}
.l_admin-push-left-medium {
  margin-left: 30px !important;
}
.l_admin-push-left-large {
  margin-left: 40px !important;
}
.l_admin-push-left-massive {
  margin-left: 60px !important;
}
.l_admin-t-left {
  text-align: left;
}
.l_admin-t-center {
  text-align: center;
}
.l_admin-t-right {
  text-align: right;
}
.l_admin-animate {
  transition: all 0.2s ease-in-out;
}

.l_admin-container {
  max-width: 1000px;
  margin: auto;
}
.l_admin-container-large {
  position: relative;
  z-index: 99;
}
.l_admin-content-small {
  padding: 10px;
}
.l_admin-content {
  padding: 20px;
  position: relative;
}
.l_admin-content-large {
  padding: 40px;
  position: relative;
}
.l_admin-content-massive {
  padding: 60px;
  position: relative;
}
.l_admin-content img {
  max-width: 100%;
  height: auto;
}
.l_admin-well {
  background: #fff;
}
.l_admin-well-alt {
  background: #f5f5f5;
  border-top: 1px solid #e5e5e5;
}

.l_admin-panel {
  margin-bottom: 20px;
  background: #fff;
  border: 1px solid #e5e5e5;
  box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.04);
}

.l_admin-button-well {
  overflow: hidden;
  padding: 10px;
  clear: both;
  border-top: 1px solid #e5e5e5;
  background: #f5f5f5;
}
.inside .l_admin-button-well {
  margin: 0px -12px -12px;
}
.l_admin-controls-title {
  padding: 8px 20px;
  background: #0074a2;
  border-bottom: 1px solid #006086;
  overflow: hidden;
  line-height: 29px;
}
.l_admin-controls-title .l_admin-heading {
  margin: 0;
  padding: 0;
  color: #fff;
  font-size: 20px;
  text-indent: 0;
  font-weight: normal;
}
.l_admin-heading {
  margin-top: 0;
  padding-top: 0;
}
.l_admin-heading a {
  text-decoration: none;
}
.l_admin-heading .l_admin-small {
  color: #777;
  font-size: 12px;
  font-weight: 400;
}
.l_admin-excerpt {
  color: #777;
}

.l_admin-section-title {
  margin-bottom: 30px;
}
.l_admin-section-title .l_admin-heading {
  margin: 0;
  padding: 0;
  font-size: 28px;
  line-height: 38px;
  color: #222;
  font-weight: 400;
}
.l_admin-section-title .l_admin-excerpt {
  max-width: 830px;
  margin: 10px 0 0;
  padding: 0;
  font-size: 20px;
  line-height: 30px;
}
.l_admin-section-title .l_admin-excerpt p {
  font-size: 20px;
  line-height: 30px;
}
.l_admin-section-title .l_admin-excerpt + .l_admin-button {
  margin-top: 20px;
}

.l_admin-section-title.l_admin-large {
  margin-bottom: 15px;
}
.l_admin-section-title.l_admin-large .l_admin-heading {
  font-size: 44px;
  line-height: 54px;
  font-weight: 300;
}
.l_admin-section-title.l_admin-large .l_admin-excerpt {
  max-width: 830px;
  font-size: 26px;
  line-height: 36px;
  font-weight: 300;
}
.l_admin-section-title.l_admin-large .l_admin-excerpt p {
  font-size: 26px;
  line-height: 36px;
  font-weight: 300;
}

.l_admin-section-title.l_admin-small {
  margin-bottom: 15px;
}
.l_admin-section-title.l_admin-small .l_admin-heading {
  font-size: 24px;
  line-height: 34px;
}
.l_admin-section-title.l_admin-small .l_admin-excerpt {
  font-size: 16px;
  line-height: 26px;
}
.l_admin-section-title.l_admin-small .l_admin-excerpt p {
  font-size: 16px;
  line-height: 26px;
}

.l_admin-section-title.l_admin-tiny {
  margin-bottom: 15px;
}
.l_admin-section-title.l_admin-tiny .l_admin-heading {
  font-size: 16px;
  line-height: 26px;
  font-weight: 700;
}
.l_admin-section-title.l_admin-tiny .l_admin-excerpt {
  margin-top: 5px !important;
  font-size: 14px;
  line-height: 24px;
  font-weight: normal;
}
.l_admin-section-title.l_admin-tiny .l_admin-excerpt p {
  font-size: 14px;
  line-height: 24px;
  font-weight: normal;
}

.l_admin-section-title.l_admin-text-center {
  text-align: center;
}
.l_admin-section-title.l_admin-text-center .l_admin-excerpt {
  margin-left: auto;
  margin-right: auto;
}

.invert.l_admin-section-title .l_admin-heading {
  color: #fff;
}
.invert.l_admin-section-title .l_admin-excerpt {
  color: rgba(255, 255, 255, 0.95);
}

.l_admin-onboard-wrapper {
  max-width: 1000px;
  margin: auto;
  padding: 40px 35px 0;
  overflow: hidden;
}

.l_admin-onboard-controllers {
  overflow: hidden;
  margin-bottom: 10px;
  text-align: center;
}
.l_admin-onboard-controllers .l_admin-button.previous-step {
  float: left;
}
.l_admin-onboard-controllers .l_admin-button.next-step {
  float: right;
}
.onboard-nav-dots {
  display: inline-block;
  line-height: 28px;
  text-align: center;
}
.onboard-nav-dots .l_admin-dot {
  display: inline-block;
  background: #ccc;
  width: 40px;
  height: 8px;
  margin: 0;
  transition: all 0.2s ease-in-out;
  outline: none;
}
.onboard-nav-dots .l_admin-dot:first-child {
  border-top-left-radius: 2px;
  border-bottom-left-radius: 2px;
}
.onboard-nav-dots .l_admin-dot:last-child {
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
}
.onboard-nav-dots .l_admin-dot:hover {
  background: #555;
}
.onboard-nav-dots .l_admin-dot.dot-active:hover {
  background: #598c04;
}
.onboard-nav-dots .l_admin-dot.dot-active {
  background: #7dc800;
}
.onboard-nav-dots .l_admin-dot:focus {
  box-shadow: none;
}

.l_admin-onboard-slider {
  overflow: hidden;
  margin-bottom: 20px;
  width: 1000px;
  height: 750px;
  position: relative;
}
.l_admin-onboard-slide {
  position: absolute;
  width: 1000px;
  transform: scale(1);
  transform: translateX(0px);
  transition: all 0.2 ease-in-out;
}
.l_admin-onboard-slide.l_admin-onboard-slide-current {
  visibility: visible;
  opacity: 1;
  z-index: 10;
}
.l_admin-onboard-slide.l_admin-onboard-slide-inactive {
  visibility: none;
  opacity: 0;
  z-index: 1;
  transform: translateX(20px);
  transform: scale(0.95);
}

.l_admin-onboard-slide .l_admin-content-large {
  min-height: 360px;
}
.l_admin-help-list {
  margin: 0;
  padding: 0;
  color: #797979;
}
.l_admin-help-list li {
  padding: 0 0 8px;
  margin: 0 0 8px;
  border-bottom: 1px solid #ddd;
  opacity: 0;
  transform: translateX(10px);
  transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
}

.l_admin-help-list li:nth-child(1) {
  transition-delay: 0.3s;
}
.l_admin-help-list li:nth-child(2) {
  transition-delay: 0.35s;
}
.l_admin-help-list li:nth-child(3) {
  transition-delay: 0.4s;
}
.l_admin-help-list li:nth-child(4) {
  transition-delay: 0.45s;
}
.l_admin-help-list li:nth-child(5) {
  transition-delay: 0.5s;
}

.l_admin-onboard-slide.l_admin-onboard-slide-current .l_admin-help-list li {
  opacity: 1;
  transform: translateX(0px);
}
.l_admin-onboard-slide-inactive .l_admin-help-list li {
  opacity: 0;
  transform: translateX(10px);
}
</style>

