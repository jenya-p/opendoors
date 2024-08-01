<x-public-layout>
    <main class="grow bg-white bg-opacity-60 py-8 md:pb-16 md:pt-12">
      <div class="px-4">
        <div class="mx-auto w-full max-w-content">

          <ul
            class="flex flex-wrap gap-x-4 gap-y-2 text-[0.75rem] font-semibold uppercase text-mid-gray"
          >
            <li
              class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
            >
              <a href="{{ route('public.home') }}" title="{{__('Home')}}" class="text-mid-gray hover:text-blue"
                >{{__('Home')}}</a
              >
            </li>

            <li
              class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
            >
              <span class="text-black">{{__('News')}}</span>
            </li>
          </ul>

          <div
            class="mt-6 grid grid-cols-2 items-center gap-6 md:grid-cols-[1fr_auto_1fr]"
          >
            <div class="whitespace-nowrap">
              <h1 class="text-[1.5rem] font-bold leading-9">{{__('News')}}</h1>
            </div>

            <div
              class="col-span-2 row-start-2 mx-auto whitespace-nowrap text-balance md:col-span-1 md:row-start-auto"
            >
                <a
                href="http://globaluni.ru/#/news/"
                title="{{__('More news')}}"
                class="text-[0.75rem] font-semibold uppercase text-blue transition-colors hover:text-black"
                >{{__('More news on the website of the Association')}}</a>
            </div>

              <!--
            <div class="justify-self-end">
              <button
                data-dialog="subscribe-dialog"
                role="button"
                class="flex h-[2.25rem] items-center justify-center rounded-full border border-pink px-6 text-[0.75rem] font-semibold uppercase text-pink transition-colors hover:bg-pink hover:text-white"
              >
                {{__('Subscribe')}}
              </button>
            </div>
            -->
          </div>

          <ul class="mt-12 flex flex-col gap-4">
              @foreach($items as $item)
                @translate($item)
            <li>
              <span
                href="{{ route('public.news.show', $item) }}"
                title=""
                class="group flex h-full select-none flex-col gap-8 rounded-2xl bg-white fill-gray-light p-8 transition-all hover:fill-blue md:gap-11"
              >
                <div class="flex grow flex-col gap-4 md:gap-[1.125rem]">
                  <a href="{{ route('public.news.show', $item) }}" class="flex flex-col gap-4">
                    <time
                      datetime="2017-02-14"
                      class="text-[0.75rem] font-medium leading-7 text-mid-gray md:text-[1rem]"
                    >
                        {{ $item->date->translatedFormat('j F Y') }}
                    </time>

                    <h2
                      class="text-[1.125rem] font-bold leading-[1.625rem] transition-colors group-hover:text-blue md:text-[1.5rem] md:leading-9"
                    >
                        {{$item->title}}
                    </h2>
                  </a>

                  <div
                    class="text-[0.875rem] font-medium leading-[1.5rem] md:text-[1rem] md:leading-7 ck-content"
                  >
                    {!! $item->summary !!}
                  </div>
                </div>

                <a href="{{ route('public.news.show', $item) }}">
                  <svg
                    width="41"
                    height="10"
                    viewBox="0 0 41 10"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M40.4666 5.0627C40.7016 4.82771 40.7016 4.44671 40.4666 4.21171L36.6371 0.382252C36.4022 0.147257 36.0212 0.147257 35.7862 0.382252C35.5512 0.617247 35.5512 0.998248 35.7862 1.23324L39.1901 4.63721L35.7862 8.04117C35.5512 8.27617 35.5512 8.65717 35.7862 8.89216C36.0212 9.12716 36.4022 9.12716 36.6371 8.89216L40.4666 5.0627ZM0.326172 5.23895H40.0411V4.03547H0.326172V5.23895Z"
                    ></path>
                  </svg>
                </a>
              </span>
            </li>
              @endforeach
          </ul>
        </div>
      </div>
    </main>

    <!-- любой dialog тэг будет открываться с загрузкой страницы если ему указать аттрибут data-active="true" -->
    <!-- это нужно чтобы после успешной отправки формы и перезагрузки страницы -->
    <!-- пользователь увидел то же модальное окно но уже с текстом об успехе -->
    <dialog
      id="subscribe-dialog"
      class="w-full bg-transparent backdrop:bg-black backdrop:bg-opacity-60"
    >
      <form
        action=""
        method="post"
        class="mx-auto flex w-full max-w-[44.25rem] flex-col gap-4 rounded-[1rem] bg-white p-6 md:p-8"
      >
        <div class="flex items-center justify-between">
          <h2 class="text-[1.25rem] font-semibold text-gray-dark">
            Subscribe to newsletter
          </h2>

          <button class="close flex h-9 w-9 items-center justify-center">
            <svg
              width="19"
              height="19"
              viewBox="0 0 19 19"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M11.1744 9.49899L18.651 2.03423C18.8745 1.81075 19 1.50766 19 1.19162C19 0.875585 18.8745 0.572491 18.651 0.349018C18.4275 0.125546 18.1244 0 17.8084 0C17.4923 0 17.1892 0.125546 16.9658 0.349018L9.50101 7.82565L2.03625 0.349018C1.81278 0.125546 1.50969 2.80593e-07 1.19365 2.82947e-07C0.87761 2.85302e-07 0.574516 0.125546 0.351043 0.349018C0.127571 0.572491 0.00202562 0.875585 0.00202562 1.19162C0.00202562 1.50766 0.127571 1.81075 0.351043 2.03423L7.82767 9.49899L0.351043 16.9637C0.23981 17.0741 0.151521 17.2053 0.0912708 17.35C0.0310203 17.4946 0 17.6497 0 17.8064C0 17.963 0.0310203 18.1181 0.0912708 18.2628C0.151521 18.4074 0.23981 18.5386 0.351043 18.649C0.461369 18.7602 0.592626 18.8485 0.737245 18.9087C0.881863 18.969 1.03698 19 1.19365 19C1.35031 19 1.50543 18.969 1.65005 18.9087C1.79467 18.8485 1.92593 18.7602 2.03625 18.649L9.50101 11.1723L16.9658 18.649C17.0761 18.7602 17.2074 18.8485 17.352 18.9087C17.4966 18.969 17.6517 19 17.8084 19C17.965 19 18.1202 18.969 18.2648 18.9087C18.4094 18.8485 18.5407 18.7602 18.651 18.649C18.7622 18.5386 18.8505 18.4074 18.9108 18.2628C18.971 18.1181 19.002 17.963 19.002 17.8064C19.002 17.6497 18.971 17.4946 18.9108 17.35C18.8505 17.2053 18.7622 17.0741 18.651 16.9637L11.1744 9.49899Z"
                fill="#2D2D37"
              ></path>
            </svg>
          </button>
        </div>

        <!-- текст который отображается в случае если форма отпарвлена успешно -->
        <!-- <div class="flex flex-col gap-6">
          <div>
            <p>
              Thank you for subscribing to our newsletter. You'll now receive
              the latest updates, news, and special offers directly in your
              inbox. We appreciate your interest and look forward to staying
              connected!
            </p>
          </div>

          <div class="flex gap-2 md:justify-end">
            <button
              class="close flex h-[2.25rem] grow basis-0 items-center justify-center rounded-full border border-pink px-6 text-[0.75rem] font-semibold uppercase text-pink transition-colors md:grow-0 md:hover:bg-pink md:hover:text-white"
            >
              Ok
            </button>
          </div>
        </div> -->

        <div class="flex flex-col gap-6">
          <div class="text-[0.75rem] font-semibold leading-[0.875rem]">
            <ul class="flex flex-col gap-5">
              <!-- data-invalid="true" опередяет какого цвета обводка у поля -->
              <li data-invalid="true" class="group flex flex-col gap-3">
                <label for="subscribe-email" class="uppercase text-mid-gray"
                  >Email</label
                >

                <input
                  type="email"
                  class="h-[2.25rem] rounded-[0.5rem] border border-mid-gray px-4 outline-none group-data-[invalid='true']:border-red group-data-[invalid='true']:text-red"
                  id="subscribe-email"
                />

                <p class="leading-5 text-red">
                  Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                  Incidunt ad rerum optio, ullam, dolores quo dolorem autem quis
                  illum impedit itaque nulla corrupti odio voluptatum earum illo
                  distinctio quibusdam eligendi.
                </p>
              </li>
            </ul>
          </div>

          <div class="flex gap-2 md:justify-end">
            <button
              type="reset"
              class="close flex h-[2.25rem] grow basis-0 items-center justify-center rounded-full border border-gray-light px-6 text-[0.75rem] font-semibold uppercase text-gray-light transition-opacity md:grow-0 md:hover:opacity-60"
            >
              Cancel
            </button>

            <button
              type="submit"
              class="flex h-[2.25rem] grow basis-0 items-center justify-center rounded-full border border-pink px-6 text-[0.75rem] font-semibold uppercase text-pink transition-colors md:grow-0 md:hover:bg-pink md:hover:text-white"
            >
              Subscribe
            </button>
          </div>
        </div>
      </form>
    </dialog>


</x-public-layout>
