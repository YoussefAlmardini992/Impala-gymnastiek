class Groep{

  constructor(groep_naam, niveau,turners) {

    this.groep_naam = groep_naam;
    this.niveau = niveau;
    this.turners = turners;
  };

  getTurner = function (voornaam) {
    for(let i = 0 ; i < this.turners.length ; i++){
      if(this.turners[i].voornaam === voornaam){
        return this.turners[i];
      }
    }
  };

  add = function (turner) {
    this.turners.push(turner);
  };
  
  delete = function (turner_of_voornaam) {
    if(turner_of_voornaam.voornaam){
    this.turners.splice(this.turners.indexOf(turner_of_voornaam),1);

    }else{
      for(let i = 0 ; i < this.turners.length ; i++){
        if(this.turners[i].voornaam === turner_of_voornaam){
          this.turners.splice(this.turners.indexOf(this.turners[i]),1);
        }
      }
    }
  }
}


