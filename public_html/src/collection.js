import Turner from './classes/turner.js';
import Groep from './classes/groep.js';
import ScoreBoard from "./classes/ScoreBoard.js";
import CurrentTurnerScreen from "./classes/currentTurnerScreen.js";
import Wedstrijd from "./classes/wedstrijd.js";

const turner1 = new Turner('rawand', 'Ras', 'Shakir', 'man');
const turner2 = new Turner('maya', 'van', 'lopijn', 'vrouw');

console.log(turner1);
console.log(turner1.oefeningen());
console.log(turner2.oefeningen());

const groep1 = new Groep('jongens','4',[turner1,turner2]);
groep1.add(new Turner('thijmen','van','achternaam','man'));

console.log("group",groep1);

const turner4 = new Turner('youssef','al','mardini','man');
groep1.add(turner4);


console.log(groep1);
console.log(groep1.getTurner('rawand'));
const ScoreBoard1 = new ScoreBoard(groep1.groep_naam, groep1.turners);


const liveWedStrijd = new Wedstrijd(groep1,[1,2,3]);

console.log(liveWedStrijd.first_Turner());

const CurrentTurnerScreen1 = new CurrentTurnerScreen(liveWedStrijd.current_Turner);

console.log(ScoreBoard1);
console.log(liveWedStrijd);
console.log(CurrentTurnerScreen1);
console.log(CurrentTurnerScreen1.turner_name);
