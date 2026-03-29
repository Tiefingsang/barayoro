@extends('layouts.app')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6">
        <div x-data="{activeTab:'profile'}" class="grid grid-cols-12 gap-4 xxl:gap-6">
          <div class="white-box xxxl:p-6 col-span-12">
            <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url(assets/images/breadcrumb-el-1.png)">
              <h2 class="mb-3 xxxl:mb-5">User Profile</h2>
              <ul class="flex flex-wrap gap-2 items-center">
                <li>
                  <a class="flex items-center gap-2" href="index.html"> <i class="las text-lg xl:text-xl xxl:text-2xl la-home shrink-0"></i> <span>Home</span></a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                  <a class="flex items-center gap-2" href="#"> <i class="las text-lg xl:text-xl xxl:text-2xl la-layer-group shrink-0"></i> <span>User</span></a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                  <a class="flex items-center gap-2 text-primary-300" href="#"> <i class="las text-lg xl:text-xl xxl:text-2xl la-layer-group shrink-0"></i> <span>Profile</span></a>
                </li>
              </ul>
            </div>
          </div>

          <!-- cover -->
          <div class="rounded-xl col-span-12 bg-neutral-0 dark:bg-neutral-904">
            <div class="rounded-t-xl h-[200px] xl:h-[260px]">
              <img src="assets/images/user-profile-cover.png" class="w-full h-full object-cover rounded-t-xl" alt="" />
            </div>
            <div class="border-b border-dashed border-neutral-30 dark:border-neutral-500">
              <div class="p-3 md:p-4 xl:p-6 xxl:p-8 flex justify-between gap-4 flex-wrap items-center">
                <div class="flex items-center gap-4 xxl:gap-6">
                  <img src="assets/images/users/user-m-1.png" width="120" class="rounded-full -mt-[70px] border-2 border-neutral-0 dark:border-neutral-500" alt="profile img" />
                  <div>
                    <h5 class="h5 mb-2 font-semibold">William James</h5>
                    <span class="text-sm">UI/UX Designer</span>
                  </div>
                </div>
                <div class="flex gap-4 lg:gap-6">
                  <button class="btn-primary">
                    <i class="las la-plus-circle"></i>
                    Follow
                  </button>
                  <button class="btn-primary-outlined">
                    <i class="lab la-facebook-messenger"></i>
                    Message
                  </button>
                </div>
              </div>
            </div>
            <div class="p-3 md:p-6 xxl:p-8">
              <div class="col-span-12 flex max-w-max flex-wrap gap-4 rounded-xl md:rounded-[40px] border border-neutral-30 p-3 dark:border-neutral-500">
                <button @click="activeTab='profile'" :class="activeTab=='profile' ?'bg-primary-300 text-neutral-0': 'bg-neutral-20 dark:bg-neutral-903'" class="rounded-full capitalize px-4 py-2 text-sm font-semibold lg:px-6 lg:py-2.5">profile</button>
                <button @click="activeTab='followers'" :class="activeTab=='followers' ?'bg-primary-300 text-neutral-0': 'bg-neutral-20 dark:bg-neutral-903'" class="rounded-full capitalize px-4 py-2 text-sm font-semibold lg:px-6 lg:py-2.5">followers</button>
                <button @click="activeTab='friends'" :class="activeTab=='friends' ?'bg-primary-300 text-neutral-0': 'bg-neutral-20 dark:bg-neutral-903'" class="rounded-full capitalize px-4 py-2 text-sm font-semibold lg:px-6 lg:py-2.5">friends</button>
                <button @click="activeTab='gallery'" :class="activeTab=='gallery' ?'bg-primary-300 text-neutral-0': 'bg-neutral-20 dark:bg-neutral-903'" class="rounded-full capitalize px-4 py-2 text-sm font-semibold lg:px-6 lg:py-2.5">gallery</button>
              </div>
            </div>
          </div>

          <!-- profile -->
          <div x-show="activeTab=='profile'" x-transition class="grid grid-cols-12 gap-4 xxl:gap-6 col-span-12">
            <div class="col-span-12 xxl:col-span-8 flex flex-col gap-4 xxl:gap-6">
              <div class="white-box">
                <h4 class="bb-dashed-n30">Recent Story</h4>
                <div
                  x-data="{
                    container:null,
                    clickNext(){
                          this.$refs.container.scrollLeft += 200;
                    },
                    clickPrev(){
                          this.$refs.container.scrollLeft -= 200;
                    },
                    }"
                  class="relative"
                >
                  <button @click="clickPrev()" class="absolute duration-300 top-1/2 z-[3] -translate-y-1/2 left-5 size-8 xxl:size-10 rounded-full bg-neutral-900 text-neutral-0"><i class="las la-angle-left"></i></button>
                  <button @click="clickNext()" class="absolute duration-300 top-1/2 z-[3] -translate-y-1/2 right-5 size-8 xxl:size-10 rounded-full bg-neutral-900 text-neutral-0"><i class="las la-angle-right"></i></button>

                  <div class="overflow-x-auto scroll-smooth" style="scrollbar-width: none" x-ref="container">
                    <div class="min-w-max w-full flex gap-4" id="gallery">
                      <div class="relative">
                        <img src="assets/images/story/story-1.png" width="174" class="rounded-xl" alt="" />
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex flex-col items-center">
                          <button class="bg-neutral-0 dark:bg-neutral-500 size-8 rounded-full f-center mb-3"><i class="las la-plus text-primary-300 text-xl"></i></button>
                          <p class="text-xs text-center text-neutral-0">Add Story</p>
                        </div>
                      </div>
                      <a href="assets/images/story/story-2.png" class="relative glightbox">
                        <img src="assets/images/story/story-2.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-3.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-3.png" class="relative glightbox">
                        <img src="assets/images/story/story-3.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-4.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-4.png" class="relative glightbox">
                        <img src="assets/images/story/story-4.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-5.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-5.png" class="relative glightbox">
                        <img src="assets/images/story/story-5.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-6.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-6.png" class="relative glightbox">
                        <img src="assets/images/story/story-6.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-5.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-7.png" class="relative glightbox">
                        <img src="assets/images/story/story-7.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-2.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-2.png" class="relative glightbox">
                        <img src="assets/images/story/story-2.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-3.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-3.png" class="relative glightbox">
                        <img src="assets/images/story/story-3.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-4.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-4.png" class="relative glightbox">
                        <img src="assets/images/story/story-4.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-5.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                      <a href="assets/images/story/story-5.png" class="relative glightbox">
                        <img src="assets/images/story/story-5.png" width="174" class="rounded-xl" alt="" />
                        <img src="assets/images/users/user-sm-6.png" width="32" class="border border-neutral-0 dark:border-neutral-904 rounded-full absolute top-3 left-3" alt="" />
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="white-box">
                <h4 class="bb-dashed-n30">Add New Post</h4>
                <div id="toolbar-one" class="flex justify-between flex-wrap gap-3 rounded-t-2xl !border-neutral-30 bg-neutral-20 dark:!border-neutral-500 dark:bg-neutral-903">
                  <span class="ql-formats">
                    <select class="ql-header">
                      <option value="1">Heading</option>
                      <option value="2">Subheading</option>
                      <option selected>Normal</option>
                    </select>
                  </span>
                  <div class="flex grow justify-end">
                    <span class="ql-formats">
                      <button class="ql-bold"></button>
                      <button class="ql-italic"></button>
                      <button class="ql-underline"></button>
                    </span>
                    <span class="ql-formats">
                      <button class="ql-list" value="ordered"></button>
                      <button class="ql-list" value="bullet"></button>
                    </span>
                    <span class="ql-formats">
                      <button class="ql-clean"></button>
                    </span>
                  </div>
                </div>
                <div id="editor-one" class="rounded-b-2xl !border-neutral-30 dark:!border-neutral-500 h-[150px]">
                  <p>Write something new ...</p>
                </div>
                <div class="mt-4 xl:mt-6 flex gap-4 flex-wrap justify-between items-center">
                  <div class="flex gap-4">
                    <button class="bg-neutral-20 rounded-full dark:bg-neutral-903 size-10 border border-neutral-40 dark:border-neutral-500 f-center">
                      <i class="las la-smile text-2xl"></i>
                    </button>
                    <button class="bg-neutral-20 rounded-full dark:bg-neutral-903 size-10 border border-neutral-40 dark:border-neutral-500 f-center">
                      <i class="las la-camera text-2xl"></i>
                    </button>
                    <button class="bg-neutral-20 rounded-full dark:bg-neutral-903 size-10 border border-neutral-40 dark:border-neutral-500 f-center">
                      <i class="las la-image text-2xl"></i>
                    </button>
                    <button class="bg-neutral-20 rounded-full dark:bg-neutral-903 size-10 border border-neutral-40 dark:border-neutral-500 f-center">
                      <i class="las la-video text-2xl"></i>
                    </button>
                    <button class="bg-neutral-20 rounded-full dark:bg-neutral-903 size-10 border border-neutral-40 dark:border-neutral-500 f-center">
                      <i class="lab la-google-drive text-2xl"></i>
                    </button>
                  </div>
                  <button class="btn-primary">Post Now</button>
                </div>
              </div>
              <div class="white-box !p-2 md:!p-4 xl:!p-8">
                <div class="flex justify-between items-center mb-4 xl:mb-6">
                  <div class="flex gap-4 items-center">
                    <img src="assets/images/users/user-s-5.png" class="rounded-full shrink-0" alt="" />
                    <div>
                      <h5 class="mb-1">Courtney Henry</h5>
                      <p class="s-text flex items-center gap-2"><span>36 Minutes</span> <i class="las la-globe text-lg"></i></p>
                    </div>
                  </div>
                  <button class="size-10 rounded-full hover:bg-neutral-20 dark:hover:bg-neutral-500 duration-300 f-center">
                    <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                  </button>
                </div>
                <p class="mb-6">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five
                  centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions
                  of Lorem Ipsum.
                </p>
                <div class="grid grid-cols-4 gap-4">
                  <div class="col-span-4 sm:col-span-2">
                    <img src="assets/images/story/story-big-1.png" class="rounded-2xl w-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2">
                    <img src="assets/images/story/story-big-2.png" class="rounded-2xl w-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <img src="assets/images/story/story-7.png" class="rounded-2xl w-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <img src="assets/images/story/story-2.png" class="rounded-2xl w-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <img src="assets/images/story/story-6.png" class="rounded-2xl w-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <img src="assets/images/story/story-5.png" class="rounded-2xl w-full" alt="" />
                  </div>
                </div>
                <div class="mt-4 xl:mt-6 flex gap-3 flex-wrap justify-between items-center">
                  <div class="flex gap-2 sm:gap-4 xl:gap-8">
                    <div class="flex items-center gap-2">
                      <button><i class="las la-thumbs-up text-2xl text-primary-300"></i></button> <span class="m-text">24k</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-comment text-2xl text-primary-300"></i></button> <span class="m-text">245</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-share-alt-square text-2xl text-primary-300"></i></button> <span class="m-text">200</span>
                    </div>
                  </div>
                  <p class="m-text font-medium">100k+ Share</p>
                </div>

                <!-- comments -->
                <div class="mt-7 xl:mt-10">
                  <h4 class="mb-6">Comments <span class="font-normal">(16)</span></h4>
                  <div class="form-input-filled mb-6">
                    <textarea id="multiline6" rows="4" placeholder="Textfield"></textarea>
                    <label for="multiline6" class="textarea">Write some of your comments</label>
                  </div>
                  <div class="flex flex-wrap gap-3 items-center justify-between bb-dashed-n30 xxl:mb-10 xxl:pb-10">
                    <div class="flex items-center divide-x rtl:divide-x-reverse divide-neutral-30 dark:divide-neutral-500">
                      <button>
                        <i class="las la-smile text-xl ltr:pr-2 rtl:pl-2"></i>
                      </button>
                      <button>
                        <i class="las la-image text-xl px-2"></i>
                      </button>
                      <button>
                        <i class="las la-file text-xl px-2"></i>
                      </button>
                      <button>
                        <i class="lab la-google-drive text-xl ltr:pl-2 rtl:pr-2"></i>
                      </button>
                    </div>
                    <a href="#" class="btn-primary">Submit Comment</a>
                  </div>

                  <!-- comment section -->
                  <div class="flex gap-2 md:gap-4 xxl:gap-6 overflow-hidden">
                    <div class="relative rounded-full shrink-0 after:absolute after:h-[95%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-14 after:left-1/2 flex items-start">
                      <img class="shrink-0 rounded-full w-full size-7 md:size-10 xl:size-12" src="assets/images/users/user-s-2.png" alt="" />
                    </div>
                    <div>
                      <div class="flex items-center gap-3 mb-2 md:mb-4">
                        <h5>Jerome Bell</h5>
                        <p class="text-neutral-500 dark:text-neutral-100 text-xs">20 hours ago</p>
                      </div>
                      <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                      <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                          <button><i class="las la-thumbs-up text-sm xl:text-xl"></i></button>
                          420
                        </div>
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                          <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                          Reply
                        </div>
                        <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                      </div>
                      <div class="flex gap-2 md:gap-4 xxl:gap-6">
                        <div class="relative shrink-0 rounded-full after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                          <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-3.png" alt="" />
                        </div>
                        <div>
                          <div class="flex items-center flex-wrap gap-3 mb-4">
                            <h5>Liam Johnson</h5>
                            <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                          </div>
                          <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                          <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                              <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                              320
                            </div>
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                              <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                              Reply
                            </div>
                            <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                          </div>
                          <!-- level three comment -->
                          <div class="flex gap-2 md:gap-4 xxl:gap-6">
                            <div class="relative rounded-full shrink-0 after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                              <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-4.png" alt="" />
                            </div>
                            <div>
                              <div class="flex items-center flex-wrap gap-3 mb-4">
                                <h5>Olivia Smith</h5>
                                <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                              </div>
                              <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                              <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                                  <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                                  221
                                </div>
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                                  <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                                  Reply
                                </div>
                                <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                              </div>
                              <!-- comment input -->
                              <form class="w-full rounded-full bg-neutral-20 dark:bg-neutral-903 border border-neutral-40 dark:border-neutral-500 p-1 flex">
                                <input type="text" class="px-2 text-xs sm:text-sm lg:px-4 sm:py-1 lg:py-2 w-full bg-transparent" placeholder="Enter Your Comment..." />
                                <button class="size-8 lg:size-10 shrink-0 rounded-full bg-primary-300 text-neutral-0 f-center">
                                  <i class="las la-arrow-right text-lg xl:text-2xl"></i>
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="bb-dashed-n30 xxl:mb-10 xxl:pb-10"></div>
                  <div class="flex gap-2 md:gap-4 xxl:gap-6 overflow-hidden">
                    <div class="relative rounded-full shrink-0 after:absolute after:h-[95%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-14 after:left-1/2 flex items-start">
                      <img class="shrink-0 rounded-full w-full size-7 md:size-10 xl:size-12" src="assets/images/users/user-s-2.png" alt="" />
                    </div>
                    <div>
                      <div class="flex items-center gap-3 mb-2 md:mb-4">
                        <h5>Jerome Bell</h5>
                        <p class="text-neutral-500 dark:text-neutral-100 text-xs">20 hours ago</p>
                      </div>
                      <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                      <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                          <button><i class="las la-thumbs-up text-sm xl:text-xl"></i></button>
                          420
                        </div>
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                          <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                          Reply
                        </div>
                        <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                      </div>
                      <div class="flex gap-2 md:gap-4 xxl:gap-6">
                        <div class="relative shrink-0 rounded-full after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                          <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-3.png" alt="" />
                        </div>
                        <div>
                          <div class="flex items-center flex-wrap gap-3 mb-4">
                            <h5>Liam Johnson</h5>
                            <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                          </div>
                          <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                          <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                              <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                              320
                            </div>
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                              <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                              Reply
                            </div>
                            <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                          </div>
                          <!-- level three comment -->
                          <div class="flex gap-2 md:gap-4 xxl:gap-6">
                            <div class="relative rounded-full shrink-0 after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                              <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-4.png" alt="" />
                            </div>
                            <div>
                              <div class="flex items-center flex-wrap gap-3 mb-4">
                                <h5>Olivia Smith</h5>
                                <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                              </div>
                              <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                              <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                                  <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                                  221
                                </div>
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                                  <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                                  Reply
                                </div>
                                <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                              </div>
                              <!-- comment input -->
                              <form class="w-full rounded-full bg-neutral-20 dark:bg-neutral-903 border border-neutral-40 dark:border-neutral-500 p-1 flex">
                                <input type="text" class="px-2 text-xs sm:text-sm lg:px-4 sm:py-1 lg:py-2 w-full bg-transparent" placeholder="Enter Your Comment..." />
                                <button class="size-8 lg:size-10 shrink-0 rounded-full bg-primary-300 text-neutral-0 f-center">
                                  <i class="las la-arrow-right text-lg xl:text-2xl"></i>
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="border-t border-dashed border-neutral-30 dark:border-neutral-500 pt-7 xl:pt-10 mt-6 xl:mt-10">
                    <a href="#" class="btn-primary-outlined">See All Comments</a>
                  </div>
                </div>
              </div>

              <div class="white-box">
                <div class="flex justify-between items-center mb-4 xl:mb-6">
                  <div class="flex gap-4 items-center">
                    <img src="assets/images/users/user-s-4.png" class="rounded-full shrink-0" alt="" />
                    <div>
                      <h5 class="mb-1">Eva Milar</h5>
                      <p class="s-text flex items-center gap-2"><span>50 Minutes</span> <i class="las la-globe text-lg"></i></p>
                    </div>
                  </div>
                  <button class="size-10 rounded-full hover:bg-neutral-20 dark:hover:bg-neutral-500 duration-300 f-center">
                    <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                  </button>
                </div>
                <p class="mb-6">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five
                  centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions
                  of Lorem Ipsum.
                </p>
                <div class="grid grid-cols-4 gap-4 md:h-[280px]">
                  <div class="col-span-4 sm:col-span-1">
                    <img src="assets/images/story/story-6.png" class="rounded-2xl w-full h-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-1">
                    <img src="assets/images/story/story-5.png" class="rounded-2xl w-full h-full" alt="" />
                  </div>
                  <div class="col-span-4 sm:col-span-2 overflow-hidden rounded-2xl">
                    <img src="assets/images/story/story-big-2.png" class="rounded-2xl w-full h-full object-cover" alt="" />
                  </div>
                </div>
                <div class="mt-4 xl:mt-6 flex gap-3 flex-wrap justify-between items-center">
                  <div class="flex gap-4 xl:gap-8">
                    <div class="flex items-center gap-2">
                      <button><i class="las la-thumbs-up text-2xl text-primary-300"></i></button> <span class="m-text">24k</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-comment text-2xl text-primary-300"></i></button> <span class="m-text">245</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-share-alt-square text-2xl text-primary-300"></i></button> <span class="m-text">200</span>
                    </div>
                  </div>
                  <p class="m-text font-medium">100k+ Share</p>
                </div>
              </div>
              <div class="white-box">
                <div class="flex justify-between items-center mb-4 xl:mb-6">
                  <div class="flex gap-4 items-center">
                    <img src="assets/images/users/user-s-2.png" class="rounded-full shrink-0" alt="" />
                    <div>
                      <h5 class="mb-1">Wade Warren</h5>
                      <p class="s-text flex items-center gap-2"><span>55 Minutes</span> <i class="las la-globe text-lg"></i></p>
                    </div>
                  </div>
                  <button class="size-10 rounded-full hover:bg-neutral-20 dark:hover:bg-neutral-500 duration-300 f-center">
                    <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                  </button>
                </div>
                <p class="mb-6">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five
                  centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions
                  of Lorem Ipsum.
                </p>
                <div class="col-span-2">
                  <img src="assets/images/story/story-big-3.png" class="rounded-2xl w-full" alt="" />
                </div>

                <div class="mt-4 xl:mt-6 flex justify-between items-center">
                  <div class="flex gap-4 xl:gap-8">
                    <div class="flex items-center gap-2">
                      <button><i class="las la-thumbs-up text-2xl text-primary-300"></i></button> <span class="m-text">24k</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-comment text-2xl text-primary-300"></i></button> <span class="m-text">245</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button><i class="las la-share-alt-square text-2xl text-primary-300"></i></button> <span class="m-text">200</span>
                    </div>
                  </div>
                  <p class="m-text font-medium">100k+ Share</p>
                </div>

                <!-- comments -->
                <div class="mt-7 xl:mt-10">
                  <h4 class="mb-6">Comments <span class="font-normal">(16)</span></h4>
                  <div class="form-input-filled mb-6">
                    <textarea id="multiline" rows="4" placeholder="Textfield"></textarea>
                    <label for="multiline" class="textarea">Write some of your comments</label>
                  </div>
                  <div class="flex items-center gap-3 flex-wrap justify-between bb-dashed-n30 xxl:mb-10 xxl:pb-10">
                    <div class="flex items-center divide-x rtl:divide-x-reverse divide-neutral-30 dark:divide-neutral-500">
                      <button>
                        <i class="las la-smile text-xl ltr:pr-2 rtl:pl-2"></i>
                      </button>
                      <button>
                        <i class="las la-image text-xl px-2"></i>
                      </button>
                      <button>
                        <i class="las la-file text-xl px-2"></i>
                      </button>
                      <button>
                        <i class="lab la-google-drive text-xl ltr:pl-2 rtl:pr-2"></i>
                      </button>
                    </div>
                    <a href="#" class="btn-primary">Submit Comment</a>
                  </div>

                  <!-- comment section -->
                  <div class="flex gap-2 md:gap-4 xxl:gap-6 overflow-hidden">
                    <div class="relative rounded-full shrink-0 after:absolute after:h-[95%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-14 after:left-1/2 flex items-start">
                      <img class="shrink-0 rounded-full w-full size-7 md:size-10 xl:size-12" src="assets/images/users/user-s-2.png" alt="" />
                    </div>
                    <div>
                      <div class="flex items-center gap-3 mb-2 md:mb-4">
                        <h5>Jerome Bell</h5>
                        <p class="text-neutral-500 dark:text-neutral-100 text-xs">20 hours ago</p>
                      </div>
                      <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                      <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                          <button><i class="las la-thumbs-up text-sm xl:text-xl"></i></button>
                          420
                        </div>
                        <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                          <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                          Reply
                        </div>
                        <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                      </div>
                      <div class="flex gap-2 md:gap-4 xxl:gap-6">
                        <div class="relative shrink-0 rounded-full after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                          <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-3.png" alt="" />
                        </div>
                        <div>
                          <div class="flex items-center flex-wrap gap-3 mb-4">
                            <h5>Liam Johnson</h5>
                            <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                          </div>
                          <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                          <div class="flex items-center gap-4 mb-4 md:mb-6 xl:mb-8">
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                              <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                              320
                            </div>
                            <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                              <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                              Reply
                            </div>
                            <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                          </div>
                          <!-- level three comment -->
                          <div class="flex gap-2 md:gap-4 xxl:gap-6">
                            <div class="relative rounded-full shrink-0 after:absolute after:h-[90%] after:w-px after:bg-neutral-80 after:top-10 after:xl:top-12 after:left-1/2 flex items-start">
                              <img class="shrink-0 rounded-full w-full size-5 md:size-7 xl:size-9" src="assets/images/users/user-s-4.png" alt="" />
                            </div>
                            <div>
                              <div class="flex items-center flex-wrap gap-3 mb-4">
                                <h5>Olivia Smith</h5>
                                <p class="text-neutral-500 text-xs md:text-sm dark:text-neutral-100">20 hours ago</p>
                              </div>
                              <p class="text-xs lg:text-base mb-4">Are you looking for comments on a blog post, social media post, photo, product, or something else?</p>
                              <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base">
                                  <button><i class="las la-thumbs-up text-sm md:text-base xl:text-xl"></i></button>
                                  221
                                </div>
                                <div class="flex items-center gap-2 text-xs lg:text-sm xl:text-base text-primary-300">
                                  <button><i class="las la-comment text-sm md:text-base xl:text-xl"></i></button>
                                  Reply
                                </div>
                                <button class="text-xs font-medium lg:text-sm xl:text-base">Report</button>
                              </div>
                              <!-- comment input -->
                              <form class="w-full rounded-full bg-neutral-20 dark:bg-neutral-903 border border-neutral-40 dark:border-neutral-500 p-1 flex">
                                <input type="text" class="px-2 text-xs sm:text-sm lg:px-4 sm:py-1 lg:py-2 w-full bg-transparent" placeholder="Enter Your Comment..." />
                                <button class="size-8 lg:size-10 shrink-0 rounded-full bg-primary-300 text-neutral-0 f-center">
                                  <i class="las la-arrow-right text-lg xl:text-2xl"></i>
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="border-t border-dashed border-neutral-30 dark:border-neutral-500 pt-7 xl:pt-10 mt-6 xl:mt-10">
                    <a href="#" class="btn-primary-outlined">See All Comments</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-span-12 xxl:col-span-4 flex flex-col gap-4 xxl:gap-6">
              <!-- biodata -->
              <div class="white-box">
                <h4 class="bb-dashed-n30">Biodata</h4>
                <div class="grid grid-cols-2 gap-5 xxl:gap-7 mb-6 xl:mb-8">
                  <div class="col-span-1 flex items-center gap-4 xxl:gap-6">
                    <span class="size-14 flex justify-center bg-primary-300/10 items-center rounded-lg border border-primary-300 text-primary-300">
                      <i class="las la-users text-4xl"></i>
                    </span>
                    <div>
                      <h4 class="text-primary-300 mb-1">239k</h4>
                      <p class="s-text">Followers</p>
                    </div>
                  </div>
                  <div class="col-span-1 flex items-center gap-4 xxl:gap-6">
                    <span class="size-14 flex justify-center bg-secondary-300/10 items-center rounded-lg border border-secondary-300 text-secondary-300">
                      <i class="las la-user-friends text-4xl"></i>
                    </span>
                    <div>
                      <h4 class="text-secondary-300 mb-1">500k</h4>
                      <p class="s-text">Following</p>
                    </div>
                  </div>
                  <div class="col-span-1 flex items-center gap-4 xxl:gap-6">
                    <span class="size-14 flex justify-center bg-warning-300/10 items-center rounded-lg border border-warning-300 text-warning-300">
                      <i class="las la-thumbs-up text-4xl"></i>
                    </span>
                    <div>
                      <h4 class="text-warning-300 mb-1">999k</h4>
                      <p class="s-text">Likes</p>
                    </div>
                  </div>
                  <div class="col-span-1 flex items-center gap-4 xxl:gap-6">
                    <span class="size-14 flex justify-center bg-error-300/10 items-center rounded-lg border border-error-300 text-error-300">
                      <i class="las la-file-invoice text-4xl"></i>
                    </span>
                    <div>
                      <h4 class="text-error-300 mb-1">450</h4>
                      <p class="s-text">Post</p>
                    </div>
                  </div>
                </div>
                <p class="s-text mb-6 xl:mb-8">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                <div class="flex flex-col gap-4">
                  <div class="flex items-center gap-4">
                    <span class="size-10 shrink-0 flex justify-center bg-success-300/10 items-center rounded-lg border border-success-300 text-success-300">
                      <i class="las la-phone-volume text-2xl"></i>
                    </span>
                    <p class="s-text">(702) 555-0122, (217) 555-0113</p>
                  </div>
                  <div class="flex items-center gap-4">
                    <span class="size-10 shrink-0 flex justify-center bg-secondary-300/10 items-center rounded-lg border border-secondary-300 text-secondary-300">
                      <i class="las la-envelope-open-text text-2xl"></i>
                    </span>
                    <p class="s-text">tim.jennings@example.com</p>
                  </div>
                  <div class="flex items-center gap-4">
                    <span class="size-10 shrink-0 flex justify-center bg-warning-300/10 items-center rounded-lg border border-warning-300 text-warning-300">
                      <i class="las la-map-marker-alt text-2xl"></i>
                    </span>
                    <p class="s-text">4140 Parker Rd. Allentown, New Mexico 31134</p>
                  </div>
                </div>
              </div>
              <!-- recent message -->
              <div class="white-box">
                <h4 class="bb-dashed-n30">Recent Message</h4>
                <div class="flex flex-col gap-5 xxl:gap-8 bb-dashed-n30">
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                      <img src="assets/images/avatar/avatar-big-1.png" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium mb-2">Devon Lane</p>
                        <p class="s-text">Use lowercase letters for better...</p>
                      </div>
                    </div>
                    <button class="text-xs font-medium lg:text-sm xl:text-base text-primary-300">Reply</button>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                      <img src="assets/images/avatar/avatar-big-2.png" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium mb-2">Kristin Watson</p>
                        <p class="s-text">Use lowercase letters for better...</p>
                      </div>
                    </div>
                    <button class="text-xs font-medium lg:text-sm xl:text-base text-primary-300">Reply</button>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                      <img src="assets/images/avatar/avatar-big-1.png" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium mb-2">Estehr Howard</p>
                        <p class="s-text">Use lowercase letters for better...</p>
                      </div>
                    </div>
                    <button class="text-xs font-medium lg:text-sm xl:text-base text-primary-300">Reply</button>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                      <img src="assets/images/avatar/avatar-big-1.png" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium mb-2">Robert Fox</p>
                        <p class="s-text">Use lowercase letters for better...</p>
                      </div>
                    </div>
                    <button class="text-xs font-medium lg:text-sm xl:text-base text-primary-300">Reply</button>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                      <img src="assets/images/avatar/avatar-big-1.png" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium mb-2">Darral Steward</p>
                        <p class="s-text">Use lowercase letters for better...</p>
                      </div>
                    </div>
                    <button class="text-xs font-medium lg:text-sm xl:text-base text-primary-300">Reply</button>
                  </div>
                </div>
                <a href="#" class="text-primary-300 flex items-center gap-2 h6">View All <i class="las la-arrow-right text-xl"></i></a>
              </div>
              <!-- social links -->
              <div class="white-box">
                <h4 class="bb-dashed-n30">Social Links</h4>
                <ul class="flex gap-4">
                  <li>
                    <a class="size-8 lg:size-10 xxl:size-12 f-center text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-facebook-f"></i></a>
                  </li>
                  <li>
                    <a class="size-8 lg:size-10 xxl:size-12 f-center text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-twitter"></i></a>
                  </li>
                  <li>
                    <a class="size-8 lg:size-10 xxl:size-12 f-center text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-instagram"></i></a>
                  </li>
                  <li>
                    <a class="size-8 lg:size-10 xxl:size-12 f-center text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-pinterest"></i></a>
                  </li>
                  <li>
                    <a class="size-8 lg:size-10 xxl:size-12 f-center text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-linkedin-in"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Followers -->
          <div x-show="activeTab=='followers'" x-transition class="col-span-12">
            <div class="white-box overflow-x-hidden">
              <div class="flex justify-between items-center bb-dashed-n30">
                <h5>Followers</h5>
                <div class="flex items-center gap-3">
                  <span>Sort By : </span>
                  <select name="sort" class="nc-select n20">
                    <option value="all">All</option>
                    <option value="following">Following</option>
                    <option value="not-following">Not Following</option>
                  </select>
                </div>
              </div>

              <div
                x-data="{
                  followers: [
                    { id: 1, name: 'Devon Lane', avatar: './assets/images/avatar/avatar-big-1.png', location: 'New York', following: true },
                    { id: 2, name: 'Emily Rodriguez', avatar: './assets/images/avatar/avatar-big-2.png', location: 'Monaston', following: false },
                    { id: 3, name: 'Emma Johnson', avatar: './assets/images/avatar/avatar-big-3.png', location: 'Los Angeles', following: true },
                    { id: 4, name: 'Michael Johnson', avatar: './assets/images/avatar/avatar-big-4.png', location: 'Chicago', following: false },
                    { id: 5, name: 'Sarah Thompson', avatar: './assets/images/avatar/avatar-big-5.png', location: 'Houston', following: true },
                    { id: 6, name: 'David Wilson', avatar: './assets/images/avatar/avatar-big-1.png', location: 'Philadelphia', following: false },
                    { id: 7, name: 'Jessica Anderson', avatar: './assets/images/avatar/avatar-big-2.png', location: 'Phoenix', following: true },
                    { id: 8, name: 'Christopher Martinez', avatar: './assets/images/avatar/avatar-big-3.png', location: 'San Antonio', following: false },
                    { id: 9, name: 'Ashley Davis', avatar: './assets/images/avatar/avatar-big-4.png', location: 'San Diego', following: true },
                    { id: 10, name: 'Matthew Taylor', avatar: './assets/images/avatar/avatar-big-5.png', location: 'Dallas', following: false },
                    { id: 11, name: 'Brittany Clark', avatar: './assets/images/avatar/avatar-big-1.png', location: 'San Jose', following: true },
                    { id: 12, name: 'Joshua Lewis', avatar: './assets/images/avatar/avatar-big-2.png', location: 'Austin', following: false },
                    { id: 13, name: 'Amanda Lee', avatar: './assets/images/avatar/avatar-big-3.png', location: 'Jacksonville', following: true },
                    { id: 14, name: 'Brandon Walker', avatar: './assets/images/avatar/avatar-big-4.png', location: 'Fort Worth', following: false },
                    { id: 15, name: 'Stephanie Hall', avatar: './assets/images/avatar/avatar-big-5.png', location: 'Columbus', following: true },
                    { id: 16, name: 'Kevin Young', avatar: './assets/images/avatar/avatar-big-1.png', location: 'Charlotte', following: false },
                    { id: 17, name: 'Melissa Allen', avatar: './assets/images/avatar/avatar-big-2.png', location: 'Detroit', following: true },
                    { id: 18, name: 'Brian King', avatar: './assets/images/avatar/avatar-big-3.png', location: 'El Paso', following: false },
                    { id: 19, name: 'Kimberly Wright', avatar: './assets/images/avatar/avatar-big-4.png', location: 'Memphis', following: true },
                    { id: 20, name: 'Andrew Green', avatar: './assets/images/avatar/avatar-big-5.png', location: 'Denver', following: false },
                    { id: 21, name: 'Lauren Hill', avatar: './assets/images/avatar/avatar-big-1.png', location: 'Washington', following: true },
                    { id: 22, name: 'Ryan Scott', avatar: './assets/images/avatar/avatar-big-2.png', location: 'Boston', following: false },
                    { id: 23, name: 'Megan Baker', avatar: './assets/images/avatar/avatar-big-3.png', location: 'Nashville', following: true },
                    { id: 24, name: 'Jacob Carter', avatar: './assets/images/avatar/avatar-big-4.png', location: 'Seattle', following: false }
                  ],
                  toggleFollow(id) {
                    const follower = this.followers.find(f => f.id === id);
                    if (follower) {
                      follower.following = !follower.following;
                    }
                  }
                }"
                class="grid grid-cols-12 gap-4 xxl:gap-6 bb-dashed-n30"
              >
                <template x-for="{id, name, avatar, location, following} in followers" :key="id">
                  <div class="n20-box !p-2 md:!p-4 xl:!p-6 flex justify-between items-center col-span-12 md:col-span-6 xxxl:col-span-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                      <img :src="avatar" width="40" height="40" class="size-10 xl:size-14 rounded-full" alt="" />
                      <div>
                        <p class="l-text font-medium text-neutral-700 dark:text-neutral-0 mb-0.5" x-text="name"></p>
                        <p class="s-text flex items-center gap-1"><i class="las la-map-marker text-lg"></i> <span x-text="location"></span></p>
                      </div>
                    </div>

                    <div @click="toggleFollow(id)">
                      <button x-show="!following" class="btn-primary-outlined">Follow</button>
                      <button x-show="following" class="h6 flex items-center gap-2 text-primary-300"><i class="las la-check text-xl"></i> Followed</button>
                    </div>
                  </div>
                </template>
              </div>

              <div x-data="pagination" class="flex flex-wrap items-center justify-center gap-3">
                <button
                  x-bind:disabled="currentPage === 1"
                  @click="changePage(currentPage - 1)"
                  class="btn-primary-icon-outlined text-primary-300 size-10 select-none hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
                >
                  <i class="las la-angle-left text-xl"></i>
                </button>

                <template x-for="page in pageNumbers">
                  <button
                    x-text="page"
                    :class=" {'btn-primary-icon bg-primary-300 !text-neutral-0':page==currentPage, 'flex size-10 text-primary-300 select-none border-primary-300 hover:bg-primary-300 items-center hover:text-neutral-0 justify-center rounded-full border duration-300 font-semibold':page!='...', 'cursor-default':page=='...'}"
                    @click="changePage(page)"
                  ></button>
                </template>
                <button
                  x-bind:disabled="currentPage === totalPages"
                  @click="changePage(currentPage + 1)"
                  class="btn-primary-icon-outlined size-10 select-none text-primary-300 hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
                >
                  <i class="las la-angle-right text-xl"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- friends -->
          <div x-show="activeTab=='friends'" x-transition class="col-span-12">
            <div class="white-box overflow-x-hidden">
              <div class="flex justify-between items-center bb-dashed-n30">
                <h5>Friends</h5>
                <div class="flex items-center gap-3">
                  <span>Sort By : </span>
                  <select name="sort" class="nc-select n20">
                    <option value="all">All</option>
                    <option value="following">Following</option>
                    <option value="not-following">Not Following</option>
                  </select>
                </div>
              </div>
              <div
                x-data="{followers:[
                  { id: 1, name: 'Devon Lane', avatar: './assets/images/avatar/avatar-big-1.png', designation: 'Software Engineer', following: true },
                  { id: 2, name: 'Ralph Stewart', avatar: './assets/images/avatar/avatar-big-2.png', designation: 'Product Manager', following: false },
                  { id: 3, name: 'Emily Rodriguez', avatar: './assets/images/avatar/avatar-big-3.png', designation: 'UI/UX Designer', following: true },
                  { id: 4, name: 'Michael Johnson', avatar: './assets/images/avatar/avatar-big-4.png', designation: 'Data Analyst', following: false },
                  { id: 5, name: 'Sarah Thompson', avatar: './assets/images/avatar/avatar-big-5.png', designation: 'Project Manager', following: true },
                  { id: 6, name: 'David Wilson', avatar: './assets/images/avatar/avatar-big-1.png', designation: 'Front-end Developer', following: false },
                  { id: 7, name: 'Jessica Anderson', avatar: './assets/images/avatar/avatar-big-2.png', designation: 'Marketing Specialist', following: true },
                  { id: 8, name: 'Christopher Martinez', avatar: './assets/images/avatar/avatar-big-3.png', designation: 'Business Analyst', following: false },
                  { id: 9, name: 'Ashley Davis', avatar: './assets/images/avatar/avatar-big-4.png', designation: 'Quality Assurance', following: true },
                  { id: 10, name: 'Matthew Taylor', avatar: './assets/images/avatar/avatar-big-5.png', designation: 'DevOps Engineer', following: false },
                  { id: 11, name: 'Brittany Clark', avatar: './assets/images/avatar/avatar-big-1.png', designation: 'Full-stack Developer', following: true },
                  { id: 12, name: 'Joshua Lewis', avatar: './assets/images/avatar/avatar-big-2.png', designation: 'Content Writer', following: false },
                  { id: 13, name: 'Amanda Lee', avatar: './assets/images/avatar/avatar-big-3.png', designation: 'Graphic Designer', following: true },
                  { id: 14, name: 'Brandon Walker', avatar: './assets/images/avatar/avatar-big-4.png', designation: 'Sales Representative', following: false },
                  { id: 15, name: 'Stephanie Hall', avatar: './assets/images/avatar/avatar-big-5.png', designation: 'HR Manager', following: true },
                  { id: 16, name: 'Kevin Young', avatar: './assets/images/avatar/avatar-big-1.png', designation: 'Customer Support', following: false },
                  { id: 17, name: 'Melissa Allen', avatar: './assets/images/avatar/avatar-big-2.png', designation: 'Financial Analyst', following: true },
                  { id: 18, name: 'Brian King', avatar: './assets/images/avatar/avatar-big-3.png', designation: 'Network Administrator', following: false },
                  { id: 19, name: 'Kimberly Wright', avatar: './assets/images/avatar/avatar-big-4.png', designation: 'Social Media Manager', following: true },
                  { id: 20, name: 'Andrew Green', avatar: './assets/images/avatar/avatar-big-5.png', designation: 'Database Administrator', following: false },
                  { id: 21, name: 'Lauren Hill', avatar: './assets/images/avatar/avatar-big-1.png', designation: 'Web Developer', following: true },
                  { id: 22, name: 'Ryan Scott', avatar: './assets/images/avatar/avatar-big-2.png', designation: 'SEO Specialist', following: false },
                  { id: 23, name: 'Megan Baker', avatar: './assets/images/avatar/avatar-big-3.png', designation: 'Digital Marketer', following: true },
                  { id: 24, name: 'Jacob Carter', avatar: './assets/images/avatar/avatar-big-4.png', designation: 'Technical Writer', following: false }
                ]
              }"
                class="grid grid-cols-12 gap-4 xxl:gap-6 bb-dashed-n30"
              >
                <template x-for="follower in followers">
                  <div class="n20-box xxl:p-10 col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 relative">
                    <button class="absolute top-6 right-6">
                      <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                    </button>
                    <div class="flex flex-col items-center text-center">
                      <div class="size-20 rounded-full relative mb-6">
                        <img :src="follower.avatar" width="80" class="rounded-full" alt="" />
                        <span class="size-5 f-center text-neutral-0 bg-primary-300 border border-neutral-0 dark:border-neutral-904 rounded-full absolute bottom-0 right-0">
                          <i class="las la-check"></i>
                        </span>
                      </div>
                      <h5 class="mb-2" x-text="follower.name"></h5>
                      <p class="mb-4 xl:mb-6 text-sm" x-text="follower.designation"></p>
                      <ul class="flex gap-4">
                        <li>
                          <a class="size-7 lg:size-9 f-center text-lg lg:text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-facebook-f"></i></a>
                        </li>
                        <li>
                          <a class="size-7 lg:size-9 f-center text-lg lg:text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-twitter"></i></a>
                        </li>
                        <li>
                          <a class="size-7 lg:size-9 f-center text-lg lg:text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-instagram"></i></a>
                        </li>
                        <li>
                          <a class="size-7 lg:size-9 f-center text-lg lg:text-2xl rounded-full border border-primary-300 text-primary-300 hover:text-neutral-0 duration-300 hover:bg-primary-300" href="#"><i class="lab la-pinterest"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </template>
              </div>
              <div x-data="pagination" class="flex flex-wrap items-center justify-center gap-3">
                <button
                  x-bind:disabled="currentPage === 1"
                  @click="changePage(currentPage - 1)"
                  class="btn-primary-icon-outlined text-primary-300 size-10 select-none hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
                >
                  <i class="las la-angle-left text-xl"></i>
                </button>

                <template x-for="page in pageNumbers">
                  <button
                    x-text="page"
                    :class=" {'btn-primary-icon bg-primary-300 !text-neutral-0':page==currentPage, 'flex size-10 text-primary-300 select-none border-primary-300 hover:bg-primary-300 items-center hover:text-neutral-0 justify-center rounded-full border duration-300 font-semibold':page!='...', 'cursor-default':page=='...'}"
                    @click="changePage(page)"
                  ></button>
                </template>
                <button
                  x-bind:disabled="currentPage === totalPages"
                  @click="changePage(currentPage + 1)"
                  class="btn-primary-icon-outlined size-10 select-none text-primary-300 hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
                >
                  <i class="las la-angle-right text-xl"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- gallery tab -->
          <div x-show="activeTab == 'gallery'" x-transition class="col-span-12 white-box">
            <div class="bb-dashed-n30 flex justify-between items-center">
              <h4>Gallery</h4>
              <div class="flex items-center gap-3">
                <span>Sort By : </span>
                <select name="sort" class="nc-select n20">
                  <option value="all">All</option>
                  <option value="new">New</option>
                  <option value="old">Old</option>
                </select>
              </div>
            </div>
            <div class="gallery grid grid-cols-12 gap-4 xxl:gap-6">
              <a href="assets/images/carousel/thumb-4.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/thumb-4.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-2.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-2.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-3.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-3.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-4.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-4.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-6.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-6.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-4.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-4.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-5.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-5.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-caption-3.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-caption-3.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-1.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-1.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-caption-1.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-caption-1.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/thumb-1.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/thumb-1.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
              <a href="assets/images/carousel/basic-caption-2.png" class="col-span-12 md:col-span-6 xxl:col-span-4 xxxl:col-span-3 glightbox">
                <img src="assets/images/carousel/basic-caption-2.png" class="w-full rounded-xl h-full object-center object-cover" alt="" />
              </a>
            </div>
            <div x-data="pagination" class="flex flex-wrap items-center justify-center gap-3 mt-6">
              <button
                x-bind:disabled="currentPage === 1"
                @click="changePage(currentPage - 1)"
                class="btn-primary-icon-outlined text-sm lg:text-base size-10 select-none hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
              >
                <i class="las la-angle-left text-xl"></i>
              </button>

              <template x-for="page in pageNumbers">
                <button
                  x-text="page"
                  :class=" {'btn-primary-icon bg-primary-300 !text-neutral-0':page==currentPage, 'flex text-sm lg:text-base size-10 select-none border-primary-300 hover:bg-primary-300 items-center hover:text-neutral-0 justify-center text-primary-300 rounded-full border duration-300 font-semibold':page!='...', 'cursor-default':page=='...'}"
                  @click="changePage(page)"
                ></button>
              </template>
              <button
                x-bind:disabled="currentPage === totalPages"
                @click="changePage(currentPage + 1)"
                class="btn-primary-icon-outlined text-sm lg:text-base size-10 select-none hover:bg-primary-300 hover:text-neutral-0 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-transparent disabled:hover:text-primary-200"
              >
                <i class="las la-angle-right text-xl"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
@endsection
