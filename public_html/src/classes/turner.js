class Turner {

  constructor(naam, geslacht,scores) {

    this.Naam = naam;
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

    this.Scores = scores;
  }
}



