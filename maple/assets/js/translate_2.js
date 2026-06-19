// Translations
// translation.js

const translations = {
  de: {
    // Home Section
    jobTitle: "Webseitenentwickler",
    homeDesc: `<span style="font-family: 'Great Vibes', cursive">Maple_</span> konzentriert sich auf den Aufbau zugänglicher und benutzerfreundlicher Webseiten.`,

    // Navigation
    about: "<span></span> ÜBER MICH",
    projects: "<span></span> PROJEKTE",
    contact: "<span></span> KONTAKT",

    // About Section
    aboutP1: `Ich bin ein leidenschaftlicher Technik-Fan und <b class="tak">Informatikstudent</b> an der <b class="tak">University of Nigeria, Nsukka (UNN)</b>. Neben dem Studium baue und style ich moderne Benutzeroberflächen <b>(UI)</b> und arbeite regelmäßig mit React, Node.js, Express.js und MongoDB.`,

    aboutP2: `Ich nutze den <b>MERN</b>-Stack <b class="tak">(MongoDB</b>, <b class="tak">Express.js</b>, <b class="tak">React</b>, <b class="tak">Node.js)</b> sowie GitHub für Entwicklung und Versionierung. Als <b class="tak">UNN-Informatikstudent</b> lerne ich ständig dazu und helfe in meiner Freizeit Anfängern, HTML und CSS zu verstehen.`,

    aboutP3: `Ich arbeite daran, mein Wissen über Backend-Systeme, <b class="tak">Server-APIs</b> und Security-Konzepte zu vertiefen. <b class="tak">Mein</b> Workflow umfasst Tools wie <b>VS Code</b> und <b>PyCharm</b>, während ich meine JavaScript- und Python-Skills ausbaue – auch mit Blick auf Chancen in <b class="tak">Deutschlands</b> Tech-Szene.`,

    aboutP4: `Ob mehrsprachiger Code, <b class="tak">Security</b> oder echte Projekte – ich bringe viel <b class="tak">Leidenschaft</b> und Disziplin mit. Als <b class="tak">Informatikstudent an der UNN</b> arbeite ich daran, global mitzuhalten und eine Zukunft aufzubauen, in der ich erschaffe, beitrage und <b class="tak">international</b> wachsen kann.`,

    // Projects Section
    cssIntroTitle: "CSS-Intro <span>&#8599;</span>",
    cssDesc: `Ein deutsch inspiriertes Projekt, basierend auf einem YouTube-Tutorial von <a href="https://youtube.com/@traversymedia?si=rlugzAA0gD6-DKwj" rel="noopener noreferrer" target="_blank">Brad Traversy</a>. Es behandelt HTML5-Syntax, VSCode-Setup, das CSS-Boxmodell und mehr.`,

    snowAnimationTitle: "Schneeanimation & Countdown <span>&#8599;</span>",
    snowDesc: `Eine einfache Website zur Visualisierung von Javascript, wie es verwendet wurde, um einen Effekt auf einer Webseite zu gestalten und einen <b class="snowButton">Schneefall-Effekt;</b> zu erzeugen. Es ist ein Neujahrs-Countdown mit Javascript-Animation. Der Schneefall stand im Fokus und das Projekt ist anfängerfreundlich.`,

    observerTitle: "Observer & Zähler <span>&#8599;</span>",
    observerDesc: `Observer- API mit JavaScript, die ein Zähleffekt beim Scrollen aktiviert.Verwendet in einem <a href="https://phlox-demo-en.netlify.app/" rel="noopener noreferrer" target="_blank">meiner</a> <a href="https://phlox.pro" rel="noopener noreferrer" target="_blank">Phlox-</a>inspirierten Projekte.`,

    pythonTitle: "Python <span>&#8599;</span>",
    pythonDesc: `Ich lerne Python zusammen mit Frameworks wie <b class="tak">Django</b> und <b class="tak">Flask</b> zur Unterstützung der Webentwicklung.`,
    repoView: "Alle Repositories ansehen...",

    // Contact Section
    contactWa: `verfügbar für Website-bezongene projekte, Fragen oder Interessen; nur ein <b class="tak">dm</b> entfernt`,
    contactSlack: `Ich bin verfügbar, um zu Open-Source-frontend-Designs <b class="tak">beizutragen</b>. Ich mochte an aufregenden Webprojekten <b class="tak">zusammenarbeiten</b>.`,

    // Footer
    bChiang: `Website von  <a href="https://brittanychiang.com/" rel="noopener noreferrer" target="_blank">Brittany Chiang</a> inspiriert`,
    techStack: "Diese Website wurde mit HTML, CSS und JavaScript erstellt.",
    footer: "Alle Rechte vorbehalten",
  },
};

const switcher = document.getElementById("language-switcher");

// All your elements stay the same here...
const elements = {
  jobTitle: document.querySelector(".info-text h3"),
  homeDesc: document.querySelector(".info-text p"),
  aboutNav: document.querySelector('a[href="#aboutPage"]'),
  projectsNav: document.querySelector('a[href="#projectsPage"]'),
  contactNav: document.querySelector('a[href="#contactPage"]'),
  aboutP1: document.querySelector(".about .aboutP1"),
  aboutP2: document.querySelector(".about .aboutP2"),
  aboutP3: document.querySelector(".about .aboutP3"),
  aboutP4: document.querySelector(".about .aboutP4"),

  cssIntroTitle: document.querySelector(".projects .pro-card .cssIntroTitle"),
  cssDesc: document.querySelector(".projects .pro-card .cssDesc"),
  snowAnimationTitle: document.querySelector(
    ".projects .pro-card .snowAnimationTitle",
  ),
  snowDesc: document.querySelector(".projects .pro-card .snowDesc"),
  observerTitle: document.querySelector(".projects .pro-card .observerTitle"),
  observerDesc: document.querySelector(".projects .pro-card .observerDesc"),
  pythonTitle: document.querySelector(".projects .pro-card .pythonTitle"),
  pythonDesc: document.querySelector(".projects .pro-card .pythonDesc"),
  repoView: document.querySelector(".projects > p > a"),
  contactWa: document.querySelector(".contact .contactWa"),
  contactSlack: document.querySelector(".contact .contactSlack"),

  bChiang: document.querySelector("footer .bChiang"),
  techStack: document.querySelector("footer .techStack"),
  footer: document.querySelector("footer .footer"),
};

// 👇 Function to apply translations
function applyTranslations(lang) {
  const t = translations[lang];

  if (!t) return;

  elements.aboutNav.innerHTML = t.about;
  elements.projectsNav.innerHTML = t.projects;
  elements.contactNav.innerHTML = t.contact;
  elements.jobTitle.innerHTML = t.jobTitle;
  elements.homeDesc.innerHTML = t.homeDesc;

  elements.aboutP1.innerHTML = t.aboutP1;
  elements.aboutP2.innerHTML = t.aboutP2;
  elements.aboutP3.innerHTML = t.aboutP3;
  elements.aboutP4.innerHTML = t.aboutP4;

  elements.cssIntroTitle.innerHTML = t.cssIntroTitle;
  elements.cssDesc.innerHTML = t.cssDesc;
  elements.snowAnimationTitle.innerHTML = t.snowAnimationTitle;
  elements.snowDesc.innerHTML = t.snowDesc;
  elements.observerTitle.innerHTML = t.observerTitle;
  elements.observerDesc.innerHTML = t.observerDesc;
  elements.pythonTitle.innerHTML = t.pythonTitle;
  elements.pythonDesc.innerHTML = t.pythonDesc;
  elements.repoView.innerHTML = t.repoView;

  elements.contactWa.innerHTML = t.contactWa;
  elements.contactSlack.innerHTML = t.contactSlack;
  elements.bChiang.innerHTML = t.bChiang;
  elements.techStack.innerHTML = t.techStack;
  elements.footer.innerHTML = t.footer;
}

// ✅ On language select
switcher.addEventListener("change", (e) => {
  const lang = e.target.value;
  localStorage.setItem("selectedLang", lang);

  if (lang === "de") {
    applyTranslations("de");
  } else {
    localStorage.removeItem("selectedLang");
    location.reload();
  }
});

// ✅ On page load
window.addEventListener("DOMContentLoaded", () => {
  const savedLang = localStorage.getItem("selectedLang");

  if (savedLang === "de") {
    switcher.value = "de";
    applyTranslations("de");
  }
});
