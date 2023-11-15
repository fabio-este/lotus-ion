import {library, dom} from '@fortawesome/fontawesome-svg-core'
import {
    faTimes,
    faBars,
    faCalendar,
    faMapMarker,
    faUser,
    faEnvelope,
    faPhone,
    faBookmark,
    faFile,
    faMicrophone
} from '@fortawesome/free-solid-svg-icons'
import {
    faFacebookF,
    faLinkedinIn,
    faXing,
    faYoutube,
} from "@fortawesome/free-brands-svg-icons";

library.add(
    faFacebookF,
    faLinkedinIn,
    faXing,
    faYoutube,
    faTimes,
    faCalendar,
    faMapMarker,
    faEnvelope,
    faUser,
    faBars,
    faPhone,
    faBookmark,
    faFile,
    faMicrophone
    )

dom.watch();

console.log('1234');