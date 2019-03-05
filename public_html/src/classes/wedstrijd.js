export default class Wedstrijd{

  constructor(groep,jurys){

    this.groep = groep;
    this.jurys = jurys;
    this.turnerIndex = 0;
    this.first_Turner = function () {
      if(groep.length > 0){
        return groep[this.turnerIndex];
      }
    };
    this.current_Turner = this.first_Turner();

  }

  Next_Turner = function () {

  }

}