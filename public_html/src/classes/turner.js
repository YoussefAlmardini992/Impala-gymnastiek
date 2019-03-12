export default class Turner {

  constructor(voornaam, tussenvoegsel, achternaam, geslacht) {

    this.voornaam = voornaam;
    this.tussenvoegsel = tussenvoegsel;
    this.achternaam = achternaam;
    this.geslacht = geslacht === 'man' ? 'man' : 'vrouw';
    this.oefeningen = function () {

      if(this.geslacht === 'man') {
          return{
            vloer: {},
            voltige: {},
            ringen: {},
            sprong: {},
            brug_gelijk: {},
            rekstok: {}
          }
      }
      else if(this.geslacht === 'vrouw') {
        return{
          vloer: {},
          sprong: {},
          evenwichtsbalk:{},
          brug_ongelijk:{}
        }
      }
    };

    this.D_score = 10;
    this.E_score = 10;
    this.N_score = 0;
    this.totaalScore = 0;
  }
}



