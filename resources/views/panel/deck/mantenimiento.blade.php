<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>¡Mala suerte 🤣!</title>
    <style>
      * {
        box-sizing: border-box;
      }

      body {
        padding: 0;
        margin: 0;
        font-family: sans-serif;
        background-color: #63ec85;
      }

      .outer_wrapper {
        position: absolute;
        width: 100%;
        height: 100vh;
        overflow: hidden;
      }

      .wrapper {
        position: absolute;
        height: calc(100vh - 100px);
        width: 100%;
        top: 0;
      }

      .ground {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 150px;
        background-color: rgb(1, 143, 96);
      }

      .cat {
        position: absolute;
        bottom: 65px;
        left: 100px;
        height: 30px;
        width: 60px;
        transition: 1.5s;
        transform-origin: center;
        background-color: transparent;
      }

      /* body */

      .body {
        position: absolute;
        height: 30px;
        width: 60px;
      }

      .face_left .body {
        animation: turn_body_left forwards 0.5s;
      }

      @keyframes turn_body_left {
        0%,
        100% {
          transform: scale(1);
        }
        50% {
          transform: scale(0.5, 1);
        }
      }

      .face_right .body {
        animation: turn_body_right forwards 0.5s;
      }

      @keyframes turn_body_right {
        0%,
        100% {
          transform: scale(1);
        }
        50% {
          transform: scale(0.5, 1);
        }
      }

      /* head */
      .cat_head {
        position: absolute;
        height: 40px;
        width: 48px;
        right: -10px;
        top: -30px;
        transition: 0.5s;
        z-index: 50;
      }

      .first_pose .cat_head,
      .face_left .cat_head {
        right: 22px;
      }

      /* tail */
      .tail {
        position: absolute;
        top: -25px;
        height: 36px;
        width: 15px;
        animation: tail_motion forwards 2s;
        transform-origin: bottom right;
      }

      @keyframes tail_motion {
        0%,
        100% {
          left: -5px;
          transform: rotate(0deg) scale(1);
        }
        50% {
          left: -10px;
          transform: rotate(-50deg) scale(-1, 1);
        }
      }

      .first_pose .tail,
      .face_left .tail {
        left: 45px;
        animation: tail_motion_alt forwards 2s;
      }

      @keyframes tail_motion_alt {
        0%,
        100% {
          left: 45px;
          transform: rotate(0deg) scale(1);
        }
        50% {
          left: 40px;
          transform: rotate(50deg) scale(-1, 1);
        }
      }

      /* legs */
      .leg {
        position: absolute;
        height: 20px;
        width: 10px;
        transform-origin: top center;
      }

      .front_legs,
      .back_legs {
        position: absolute;
        height: 30px;
        transition: 0.7s;
      }

      .front_legs {
        width: 30px;
        right: 0;
      }

      .back_legs {
        width: 25px;
        left: 0;
      }

      .face_left .leg svg {
        transform: scale(-1, 1);
      }

      .face_right .front_legs {
        right: 0;
      }

      .first_pose .front_legs,
      .face_left .front_legs {
        right: 30px;
      }

      .face_right .back_legs {
        left: 0;
      }

      .first_pose .back_legs,
      .face_left .back_legs {
        left: 35px;
      }

      .one,
      .three {
        bottom: -15px;
        right: 0;
      }

      .two,
      .four {
        bottom: -15px;
        left: 0px;
      }

      .one.walk,
      .three.walk {
        animation: infinite 0.3s walk;
      }

      .two.walk,
      .four.walk {
        animation: infinite 0.3s walk_alt;
      }

      @keyframes walk {
        0%,
        100% {
          transform: rotate(-10deg);
        }
        50% {
          transform: rotate(10deg);
        }
      }

      @keyframes walk_alt {
        0%,
        100% {
          transform: rotate(10deg);
        }
        50% {
          transform: rotate(-10deg);
        }
      }

      /* jump */
      .cat_wrapper {
        position: absolute;
        bottom: 0;
      }

      .cat_wrapper.jump .one {
        animation: infinite 0.3s walk;
      }

      .cat_wrapper.jump .two {
        animation: infinite 0.3s walk_alt;
      }

      .cat_wrapper.jump .three,
      .cat_wrapper.jump .four {
        animation: none;
      }

      .cat_wrapper.jump .cat.face_right .back_legs {
        transform-origin: center;
        transform: rotate(50deg);
      }

      .cat_wrapper.jump .cat.face_left .back_legs {
        transform-origin: center;
        transform: rotate(-50deg);
      }

      .cat_wrapper.jump .cat.face_right .front_legs {
        transform-origin: center;
        transform: rotate(-60deg);
      }

      .cat_wrapper.jump .cat.face_left .front_legs {
        transform-origin: center;
        transform: rotate(60deg);
      }

      .cat_wrapper.jump {
        animation: jump forwards 1s;
      }

      @keyframes jump {
        0%,
        100% {
          bottom: 0px;
        }
        50% {
          bottom: 200px;
        }
      }

      .jump .face_left {
        animation: forwards 0.5s body_stand_left;
        transform-origin: right bottom;
      }

      .jump .face_right {
        animation: forwards 0.5s body_stand_right;
        transform-origin: left bottom;
      }

      @keyframes body_stand_right {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(-45deg);
        }
      }

      @keyframes body_stand_left {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(45deg);
        }
      }

      svg {
        height: 100%;
        width: 100%;
      }

      polygon.eyes {
        fill: rgb(1, 143, 96);
      }

      polygon,
      path {
        fill: white;
      }

      .sign {
        position: absolute;
        color: white;
        bottom: 10px;
        right: 10px;
        font-size: 10px;
      }

      a {
        color: white;
        text-decoration: none;
      }

      a:hover {
        text-decoration: underline;
      }
      .titulo{
        color:white;
        display:flex;
        justify-content: center;
        position: absolute;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <div class="titulo">
      <h1>
        Lo sentimos, el Deck se encuentra en mantenimiento.
        Vuelve pronto.
      </h1>
    </div>
    <!-- partial:index.partial.html -->
    <body>
      <div class="outer_wrapper">
        <div class="wrapper">
          <div class="cat_wrapper">
            <div class="cat first_pose">
              <div class="cat_head">
                <svg
                  x="0px"
                  y="0px"
                  width="100%"
                  height="100%"
                  viewBox="0 0 76.4 61.2"
                >
                  <polygon
                    class="eyes"
                    points="63.8,54.1 50.7,54.1 50.7,59.6 27.1,59.6 27.1,54.1 12.4,54.1 12.4,31.8 63.8,31.8 "
                  />
                  <path
                    d="M15.3,45.9h5.1V35.7h-5.1C15.3,35.7,15.3,45.9,15.3,45.9z M45.8,56.1V51H30.6v5.1H45.8z M61.1,35.7H56v10.2h5.1
                V35.7z M10.2,61.2v-5.1H5.1V51H0V25.5h5.1V15.3h5.1V5.1h5.1V0h5.1v5.1h5.1v5.1h5.1v5.1c0,0,15.2,0,15.2,0v-5.1h5.1V5.1H56V0h5.1v5.1
                h5.1v10.2h5.1v10.2h5.1l0,25.5h-5.1v5.1h-5.1v5.1H10.2z"
                  />
                </svg>
              </div>
              <div class="body">
                <svg
                  x="0px"
                  y="0px"
                  width="100%"
                  height="100%"
                  viewBox="0 0 91.7 40.8"
                >
                  <path
                    class="st0"
                    d="M91.7,40.8H0V10.2h5.1V5.1h5.1V0h66.2v5.1h10.2v5.1h5.1L91.7,40.8z"
                  />
                </svg>

                <div class="tail">
                  <svg
                    x="0px"
                    y="0px"
                    width="100%"
                    height="100%"
                    viewBox="0 0 25.5 61.1"
                  >
                    <polygon
                      class="st0"
                      points="10.2,56 10.2,50.9 5.1,50.9 5.1,40.7 0,40.7 0,20.4 5.1,20.4 5.1,10.2 10.2,10.2 10.2,5.1 15.3,5.1 
                  15.3,0 25.5,0 25.5,10.2 20.4,10.2 20.4,15.3 15.3,15.3 15.3,20.4 10.2,20.4 10.2,40.7 15.3,40.7 15.3,45.8 20.4,45.8 20.4,50.9 
                  25.5,50.9 25.5,61.1 15.3,61.1 15.3,56 "
                    />
                  </svg>
                </div>
              </div>

              <div class="front_legs">
                <div class="leg one">
                  <svg
                    x="0px"
                    y="0px"
                    width="100%"
                    height="100%"
                    viewBox="0 0 14 30.5"
                  >
                    <polygon
                      points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "
                    />
                  </svg>
                </div>
                <div class="leg two">
                  <svg
                    x="0px"
                    y="0px"
                    width="100%"
                    height="100%"
                    viewBox="0 0 14 30.5"
                  >
                    <polygon
                      points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "
                    />
                  </svg>
                </div>
              </div>

              <div class="back_legs">
                <div class="leg three">
                  <svg
                    x="0px"
                    y="0px"
                    width="100%"
                    height="100%"
                    viewBox="0 0 14 30.5"
                  >
                    <polygon
                      points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "
                    />
                  </svg>
                </div>
                <div class="leg four">
                  <svg
                    x="0px"
                    y="0px"
                    width="100%"
                    height="100%"
                    viewBox="0 0 14 30.5"
                  >
                    <polygon
                      points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "
                    />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="ground"></div>
      </div>
    </body>
    <!-- partial -->
    <script>
      function init() {
        const catWrapper = document.querySelector(".cat_wrapper");
        const wrapper = document.querySelector(".wrapper");
        const cat = document.querySelector(".cat");
        const head = document.querySelector(".cat_head");
        const legs = document.querySelectorAll(".leg");
        const pos = {
          x: null,
          y: null,
        };

        const walk = () => {
          cat.classList.remove("first_pose");
          legs.forEach((leg) => leg.classList.add("walk"));
        };

        const handleMouseMotion = (e) => {
          pos.x = e.clientX;
          pos.y = e.clientY;
          walk();
        };

        const handleTouchMotion = (e) => {
          if (e.targetTouches) return;

          pos.x = e.targetTouches[0].offsetX;
          pos.y = e.targetTouches[0].offsetY;
          walk();
        };

        const turnRight = () => {
          cat.style.left = `${pos.x - 90}px`;
          cat.classList.remove("face_left");
          cat.classList.add("face_right");
        };

        const turnLeft = () => {
          cat.style.left = `${pos.x + 10}px`;
          cat.classList.remove("face_right");
          cat.classList.add("face_left");
        };

        const decideTurnDirection = () => {
          cat.getBoundingClientRect().x < pos.x ? turnRight() : turnLeft();
        };

        const headMotion = () => {
          pos.y > wrapper.clientHeight - 100
            ? (head.style.top = "-15px")
            : (head.style.top = "-30px");
        };

        const jump = () => {
          catWrapper.classList.remove("jump");
          if (pos.y < wrapper.clientHeight - 250) {
            setTimeout(() => {
              catWrapper.classList.add("jump");
            }, 100);
          }
        };

        const decideStop = () => {
          if (
            (cat.classList.contains("face_right") &&
              pos.x - 90 === cat.offsetLeft) ||
            (cat.classList.contains("face_left") &&
              pos.x + 10 === cat.offsetLeft)
          ) {
            legs.forEach((leg) => leg.classList.remove("walk"));
          }
        };

        setInterval(() => {
          if (!pos.x || !pos.y) return;
          decideTurnDirection();
          headMotion();
          decideStop();
        }, 100);

        setInterval(() => {
          if (!pos.x || !pos.y) return;
          jump();
        }, 1000);

        document.addEventListener("mousemove", handleMouseMotion);
        document.addEventListener("mousemove", handleTouchMotion);
      }

      window.addEventListener("DOMContentLoaded", init);
    </script>
  </body>
</html>
