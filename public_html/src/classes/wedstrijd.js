export default class Wedstrijd{

  constructor(groep,jurys){

    this.groep = groep;
    this.jurys = jurys;
    this.cuurent_turnerIndex = 0;
    this.first_Turner = function () {
      if(groep.turners.length > 0){
        return groep.turners[this.cuurent_turnerIndex];
      }
    };
    this.current_Turner = this.first_Turner();
  }

  Move_To_Next_Turner = function () {
    this.cuurent_turnerIndex =  this.cuurent_turnerIndex + 1;
    if(this.cuurent_turnerIndex !== this.groep.turners.length){
      this.current_Turner = this.groep.turners[this.cuurent_turnerIndex];
    }else{
      console.error("de vorige turner was alle laatste");
    }
  };

}