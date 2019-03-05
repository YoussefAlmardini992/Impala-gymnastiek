export default class CurrentTurnerScreen{

  constructor(current_turner){
    this.turner_name = current_turner.voornaam + " " + current_turner.tussenvoegsel + " " + current_turner.achternaam;
    this.E_score = current_turner.E_score;
    this.D_score = current_turner.D_score;
    this.N_score = current_turner.N_score;
    this.totalScore = current_turner.totalScore;
  }

}